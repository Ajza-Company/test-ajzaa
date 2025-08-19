<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlatCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear existing categories first
        DB::table('category_locales')->delete();
        DB::table('categories')->delete();

        // Assuming locale IDs: 1 for English, 2 for Arabic
        $enLocaleId = 1;
        $arLocaleId = 2;

        $now = Carbon::now();

        // Only 4 main categories - no parent/child structure
        $categories = [
            [
                "name" => "Car Parts",
                "name_ar" => "قطع غيار السيارات",
                "order_prefix" => "CP",
                "sort_order" => 1
            ],
            [
                "name" => "Car Covers",
                "name_ar" => "كفرات السيارات",
                "order_prefix" => "CC",
                "sort_order" => 2
            ],
            [
                "name" => "Decorations and accessories",
                "name_ar" => "الزينة والاكسسوارات",
                "order_prefix" => "DA",
                "sort_order" => 3
            ],
            [
                "name" => "Oil Filters",
                "name_ar" => "فلاتر زيت",
                "order_prefix" => "OF",
                "sort_order" => 4
            ]
        ];

        foreach ($categories as $category) {
            // Insert main category (no parent_id)
            $categoryId = DB::table('categories')->insertGetId([
                'order_prefix' => $category['order_prefix'],
                'sort_order' => $category['sort_order'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert category locales
            DB::table('category_locales')->insert([
                [
                    'category_id' => $categoryId,
                    'locale_id' => $enLocaleId,
                    'name' => $category['name'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'category_id' => $categoryId,
                    'locale_id' => $arLocaleId,
                    'name' => $category['name_ar'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ]);
        }

        $this->command->info('Flat categories seeded successfully! Only 4 main categories created.');
    }
}
