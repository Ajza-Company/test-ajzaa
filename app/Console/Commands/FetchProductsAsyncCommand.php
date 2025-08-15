<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\CarModel;
use App\Models\CarBrand;
use App\Models\Product;
use App\Models\ProductLocale;
use App\Models\ProductCarAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Async\Pool;

class FetchProductsAsyncCommand extends Command
{
    protected $signature = 'products:fetch:sync2';
    protected $description = 'Fetch products from Rafraf API for all car models and subcategories';

    protected $carModelsData = [];

    public function handle(): int
    {
        $this->loadCarModelsData();

        $subCategories = Category::whereNotNull('parent_id')->get();
        $this->info('Starting to fetch products...');

        $activeBrands = CarBrand::all();
        $this->info("Found " . $activeBrands->count() . " active car brands");

        $enLocaleId = 1;
        $arLocaleId = 2;

        $pool = Pool::create()->concurrency(8); // Adjust concurrency as needed

        foreach ($activeBrands as $brand) {
            $this->info("Processing car brand: {$brand->id} ({$brand->localized?->name})");

            $brandData = $this->findBrandInJson($brand);

            if (!$brandData) {
                $this->warn("  Brand not found in JSON data, skipping...");
                continue;
            }

            foreach ($brandData['children'] as $modelData) {
                $this->info("  Processing car model: {$modelData['name']} (ID: {$modelData['id']})");

                $carModel = CarModel::firstOrCreate(
                    ['external_id' => $modelData['id']],
                    [
                        'car_brand_id' => $brand->id,
                        'name' => $modelData['name'],
                        'is_active' => true
                    ]
                );

                foreach ($modelData['children'] as $yearData) {
                    $year = $yearData['name'];
                    $this->info("    Processing year: {$year} (ID: {$yearData['id']})");

                    foreach ($subCategories as $category) {
                        $this->info("      Processing category: {$category->name} (ID: {$category->external_id})");
                        $pool->add(function () use ($category, $carModel, $brand, $year, $yearData, $enLocaleId, $arLocaleId) {
                            $currentPage = 1;
                            $totalPages = 1;

                            while ($currentPage <= $totalPages) {
                                $this->info("        Fetching page {$currentPage}...");
                                try {
                                    $this->info("        Fetching page {$currentPage}...");
                                    $response = $this->fetchProducts($category->external_id, $yearData['id'], $currentPage);

                                    if (!isset($response['data']['products'])) {
                                        continue;
                                    }

                                    $products = $response['data']['products'];
                                    $totalPages = $products['page_info']['total_pages'];
                                    $pageSize = $products['page_info']['page_size'] ?? 50;
                                    $itemsCount = count($products['items']);

                                    foreach ($products['items'] as $item) {
                                        DB::transaction(function () use ($item, $category, $carModel, $brand, $year, $enLocaleId, $arLocaleId) {
                                            $existingProduct = Product::where('part_number', $item['sku'])->first();

                                            if ($existingProduct) {
                                                $exists = ProductCarAttribute::where('product_id', $existingProduct->id)
                                                    ->where('car_model_id', $carModel->id)
                                                    ->where('year', $year)
                                                    ->exists();

                                                if (!$exists) {
                                                    ProductCarAttribute::create([
                                                        'product_id' => $existingProduct->id,
                                                        'car_brand_id' => $brand->id,
                                                        'car_model_id' => $carModel->id,
                                                        'year' => $year
                                                    ]);
                                                }
                                            } else {
                                                $price = $item['price_range']['minimum_price']['final_price']['value'] ?? 0;
                                                $imageUrl = $item['image']['url'] ?? null;

                                                $product = Product::create([
                                                    'category_id' => $category->id,
                                                    'part_number' => $item['sku'],
                                                    'image' => $imageUrl,
                                                    'price' => $price,
                                                    'is_original' => true,
                                                    'is_active' => true,
                                                    'is_default' => true,
                                                ]);

                                                ProductLocale::create([
                                                    'product_id' => $product->id,
                                                    'locale_id' => $enLocaleId,
                                                    'name' => $item['name'],
                                                    'description' => $item['description']['html'] ?? null,
                                                ]);

                                                ProductLocale::create([
                                                    'product_id' => $product->id,
                                                    'locale_id' => $arLocaleId,
                                                    'name' => $item['name'],
                                                    'description' => $item['description']['html'] ?? null,
                                                ]);

                                                ProductCarAttribute::create([
                                                    'product_id' => $product->id,
                                                    'car_brand_id' => $brand->id,
                                                    'car_model_id' => $carModel->id,
                                                    'year' => $year
                                                ]);
                                            }
                                        });
                                    }

                                    $currentPage++;

                                    // Optional: sleep(2); // You may want to remove or reduce this delay

                                    if ($itemsCount < $pageSize) {
                                        break;
                                    }
                                } catch (\Exception $e) {
                                    // Log or handle error
                                    $this->error("Error: Brand {$brand->id}, Model {$carModel->id}, Year {$year}, Category {$category->id}: " . $e->getMessage());
                                    continue;
                                }
                            }
                        })->then(function () use ($category, $carModel, $brand, $year) {
                            // Success callback (optional)
                            $this->info("Finished: Brand {$brand->id}, Model {$carModel->id}, Year {$year}, Category {$category->id}");
                        })->catch(function (\Throwable $exception) use ($category, $carModel, $brand, $year) {
                            // Error callback
                            $this->error("Error: Brand {$brand->id}, Model {$carModel->id}, Year {$year}, Category {$category->id}: " . $exception->getMessage());
                        });
                    }
                }
            }
        }

        $pool->wait();

        $this->info('Product fetching completed successfully!');
        return 0;
    }

    protected function loadCarModelsData(): void
    {
        $this->carModelsData = json_decode(File::get("database/data/carCategories.json"), true);
        $this->info("Loaded car models data from JSON file");
    }

    protected function findBrandInJson($brand)
    {
        foreach ($this->carModelsData as $brandData) {
            if ($brandData['id'] == $brand->external_id ||
                strtolower($brandData['name']) == strtolower($brand->localized?->name)) {
                return $brandData;
            }
        }
        return null;
    }

    protected function fetchProducts($categoryId, $carModelId, $currentPage = 1)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.rafraf.com/graphql', [
            'query' => '
              query getAllProducts ($search: String, $filter:ProductAttributeFilterInput, $sort: ProductAttributeSortInput, $currentPage: Int) {
                products(
                  search: $search,
                  filter: $filter,
                  sort: $sort,
                  currentPage: $currentPage,
                  pageSize: 50
                ) {
                  page_info {
                    current_page
                    page_size
                    total_pages
                  }
                  total_count
                  items {
                    stock_status
                    id
                    sku
                    name
                    part_manufacturer_store
                    description {
                      html
                    }
                    url_key
                    image {
                      label
                      url
                    }
                    part_type_new
                    price_range {
                      maximum_price {
                        final_price {
                          currency
                          value
                        }
                        discount {
                          amount_off
                        }
                        fixed_product_taxes {
                          amount {
                            currency
                            value
                          }
                          label
                        }
                        regular_price {
                          currency
                          value
                        }
                      }
                      minimum_price {
                        final_price {
                          currency
                          value
                        }
                        discount {
                          amount_off
                        }
                        fixed_product_taxes {
                          amount {
                            currency
                            value
                          }
                          label
                        }
                        regular_price {
                          currency
                          value
                        }
                      }
                    }
                    related_products {
                      id
                      name
                      sku
                      url_key
                      __typename
                    }
                    ... on ConfigurableProduct {
                      variants {
                        attributes {
                          code
                          uid
                          value_index
                          label
                        }
                      }
                    }
                  }
                }
              }
            ',
            'variables' => [
                'currentPage' => (string)$currentPage,
                'filter' => [
                    'category_id' => [
                        'in' => [$categoryId, $carModelId]
                    ],
                    'featured' => [
                        'in' => []
                    ],
                    'part_manufacturer_store' => [
                        'in' => []
                    ],
                    'price' => [],
                    'part_type_new' => [
                        'in' => []
                    ]
                ],
                'sort' => []
            ]
        ]);

        return $response->json();
    }
}
