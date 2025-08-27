<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductLocale;
use Illuminate\Database\Seeder;

class CustomCategoriesTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure we have at least one company
        $company = Company::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Test Company',
                'email' => 'test@company.com',
                'phone' => '+966501234567',
                'is_active' => true,
                'is_approved' => true
            ]
        );

        // Create system categories (if they don't exist)
        $this->createSystemCategories();

        // Create custom categories for the company
        $this->createCustomCategories($company->id);

        // Create some test products
        $this->createTestProducts($company->id);
    }

    /**
     * Create system categories
     */
    private function createSystemCategories(): void
    {
        $systemCategories = [
            [
                'name' => 'سيارات',
                'image' => 'cars.jpg',
                'is_active' => true,
                'sort_order' => 0
            ],
            [
                'name' => 'قطع غيار',
                'image' => 'parts.jpg',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'إكسسوارات',
                'image' => 'accessories.jpg',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'زيوت ومواد تشحيم',
                'image' => 'oils.jpg',
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($systemCategories as $categoryData) {
            $category = Category::firstOrCreate(
                [
                    'category_type' => 'system',
                    'company_id' => null
                ],
                [
                    'image' => $categoryData['image'],
                    'is_active' => $categoryData['is_active'],
                    'sort_order' => $categoryData['sort_order']
                ]
            );

            // Create locale record
            $category->translations()->firstOrCreate(
                [
                    'locale_id' => 1,
                    'category_id' => $category->id
                ],
                [
                    'name' => $categoryData['name']
                ]
            );
        }
    }

    /**
     * Create custom categories for a company
     */
    private function createCustomCategories(int $companyId): void
    {
        $customCategories = [
            [
                'name' => 'سيارات فاخرة',
                'description' => 'قسم السيارات الفاخرة والرياضية عالية الجودة',
                'image' => 'luxury-cars.jpg',
                'is_active' => true,
                'sort_order' => 0
            ],
            [
                'name' => 'قطع غيار أصلية',
                'description' => 'قطع غيار أصلية من الشركات المصنعة',
                'image' => 'original-parts.jpg',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'سيارات كلاسيكية',
                'description' => 'سيارات كلاسيكية نادرة ومميزة',
                'image' => 'classic-cars.jpg',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'إكسسوارات فاخرة',
                'description' => 'إكسسوارات فاخرة للسيارات',
                'image' => 'luxury-accessories.jpg',
                'is_active' => false,
                'sort_order' => 3
            ],
            [
                'name' => 'خدمات الصيانة',
                'description' => 'خدمات صيانة وإصلاح السيارات',
                'image' => 'maintenance.jpg',
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($customCategories as $categoryData) {
            $category = Category::create([
                'category_type' => 'custom',
                'company_id' => $companyId,
                'image' => $categoryData['image'],
                'is_active' => $categoryData['is_active'],
                'sort_order' => $categoryData['sort_order']
            ]);

            // Create locale record
            $category->translations()->create([
                'name' => $categoryData['name'],
                'locale_id' => 1,
                'category_id' => $category->id
            ]);
        }
    }

    /**
     * Create test products
     */
    private function createTestProducts(int $companyId): void
    {
        // Get some categories to assign products to
        $systemCategory = Category::where('category_type', 'system')->first();
        $customCategory = Category::where('category_type', 'custom')->where('company_id', $companyId)->first();

        if ($systemCategory) {
            $this->createProduct('زيت محرك 5W-30', 'زيت محرك عالي الجودة', $systemCategory->id, 150.00);
            $this->createProduct('فلتر هواء', 'فلتر هواء للمحرك', $systemCategory->id, 45.00);
        }

        if ($customCategory) {
            $this->createProduct('مرسيدس AMG GT', 'سيارة رياضية فاخرة', $customCategory->id, 2500000.00);
            $this->createProduct('BMW M4', 'سيارة رياضية ألمانية', $customCategory->id, 1800000.00);
            $this->createProduct('أودي RS6', 'سيارة عائلية رياضية', $customCategory->id, 2200000.00);
        }
    }

    /**
     * Create a single product
     */
    private function createProduct(string $name, string $description, int $categoryId, float $price): void
    {
        $product = Product::create([
            'category_id' => $categoryId,
            'price' => $price,
            'is_active' => true,
            'is_original' => true
        ]);

        // Create product locale
        $product->translations()->create([
            'name' => $name,
            'description' => $description,
            'locale_id' => 1,
            'product_id' => $product->id
        ]);
    }
}
