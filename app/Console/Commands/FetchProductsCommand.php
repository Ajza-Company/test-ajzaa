<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Http;
use App\Models\CarModel;
use App\Models\CarBrand;
use App\Models\Product;
use App\Models\ProductLocale;
use App\Models\ProductCarAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FetchProductsCommand extends Command
{
    protected $signature = 'products:fetch';
    protected $description = 'Fetch products from Rafraf API for all car models and subcategories';

    // Car models data from JSON file
    protected $carModelsData = [];

    /**
     * @throws FileNotFoundException
     * @throws \Throwable
     */
    public function handle(): int
    {
        // Load car models data from JSON file
        $this->loadCarModelsData();

        $subCategories = Category::whereNotNull('parent_id')->get();

        $this->info('Starting to fetch products...');

        // Get active car brands
        $activeBrands = CarBrand::where('is_active', true)->where('id', '>', 7)->get();
        $this->info("Found " . $activeBrands->count() . " active car brands");

        // Get locale IDs
        $enLocaleId = 1;
        $arLocaleId = 2;

        $processedCount = 0;

        // Process each active car brand
        foreach ($activeBrands as $brand) {
            $this->info("Processing car brand: {$brand->id} ({$brand->localized?->name})");

            // Find brand in JSON data
            $brandData = $this->findBrandInJson($brand);

            if (!$brandData) {
                $this->warn("  Brand not found in JSON data, skipping...");
                continue;
            }

            // Process each model for this brand
            foreach ($brandData['children'] as $modelData) {
                $this->info("  Processing car model: {$modelData['name']} (ID: {$modelData['id']})");

                // Find or create car model in database
                $carModel = CarModel::firstOrCreate(
                    ['external_id' => $modelData['id']],
                    [
                        'car_brand_id' => $brand->id,
                        'name' => $modelData['name'],
                        'is_active' => true
                    ]
                );

                // Process each year for this model
                foreach ($modelData['children'] as $yearData) {
                    $year = $yearData['name'];
                    $this->info("    Processing year: {$year} (ID: {$yearData['id']})");

                    foreach ($subCategories as $category) {
                        $this->info("      Processing category: {$category->localized?->name} (ID: {$category->external_id})");

                        $currentPage = 1;
                        $totalPages = 1;

                        // Fetch all pages for this combination
                        while ($currentPage <= $totalPages) {
                            $this->info("        Fetching page {$currentPage}...");

                            try {
                                $this->info('          Fetching products... category: ' . $category->external_id . ' car model: ' . $yearData['id']);
                                $response = $this->fetchProducts($category->external_id, $yearData['id'], $currentPage);

                                if (!isset($response['data']['products'])) {
                                    $this->error("        Invalid response format for category {$category->external_id} and car model {$yearData['id']}");
                                    continue;
                                }

                                $products = $response['data']['products'];
                                $totalPages = $products['page_info']['total_pages'];
                                $pageSize = $products['page_info']['page_size'] ?? 50;
                                $itemsCount = count($products['items']);

                                $this->info("        Found {$products['total_count']} products (Page {$currentPage} of {$totalPages})");

                                // Process products
                                foreach ($products['items'] as $item) {
                                    $this->info('          Processing product...' . $item['sku']);
                                    DB::transaction(function () use ($item, $category, $carModel, $brand, $year, $enLocaleId, $arLocaleId) {
                                        // Check if product already exists by SKU
                                        $existingProduct = Product::where('part_number', $item['sku'])->first();

                                        if ($existingProduct) {
                                            $this->info('          Product Exists... ' . $existingProduct->id);

                                            // Check if car model relation with this year already exists
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
                                                $this->info('          Added new year relation: ' . $year);
                                            } else {
                                                $this->info('          Year relation already exists for this ' . $existingProduct->id . ' and model ' . $carModel->id . ' and year ' . $year);
                                            }
                                        } else {
                                            $this->info('          Creating new product...');

                                            // Create new product
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

                                            // Create product locales
                                            ProductLocale::create([
                                                'product_id' => $product->id,
                                                'locale_id' => $enLocaleId,
                                                'name' => $item['name'],
                                                'description' => $item['description']['html'] ?? null,
                                            ]);

                                            // Create Arabic locale (using same data for now)
                                            ProductLocale::create([
                                                'product_id' => $product->id,
                                                'locale_id' => $arLocaleId,
                                                'name' => $item['name'],
                                                'description' => $item['description']['html'] ?? null,
                                            ]);

                                            // Create car attribute relation with year
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

                                // Add a small delay to avoid overwhelming the API
                                sleep(2);

                                // Optimization: If fewer items than page size, break early
                                if ($itemsCount < $pageSize) {
                                    $this->info("        Fewer products ({$itemsCount}) than page size ({$pageSize}) returned. No more products to fetch for this combination.");
                                    break;
                                }

                            } catch (\Exception $e) {
                                $this->error("        Error fetching products: " . $e->getMessage());
                                continue;
                            }
                        }
                    }
                }
            }

            $processedCount++;
            $this->info("Completed {$processedCount}/{$activeBrands->count()} car brands");
        }

        $this->info('Product fetching completed successfully!');
        return 0;
    }

    /**
     * Load car models data from JSON file
     * @throws FileNotFoundException
     */
    protected function loadCarModelsData(): void
    {
        $this->carModelsData = json_decode(File::get("database/data/carCategories.json"), true);
        $this->info("Loaded car models data from JSON file");
    }

    /**
     * Find brand data in JSON by matching name or ID
     */
    protected function findBrandInJson($brand)
    {
        foreach ($this->carModelsData as $brandData) {
            // Match by ID or name (you might need to adjust this logic based on your data)
            if ($brandData['id'] == $brand->external_id ||
                strtolower($brandData['name']) == strtolower($brand->localized?->name)) {
                return $brandData;
            }
        }

        return null;
    }

    /**
     * Fetch products from the API
     */
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
