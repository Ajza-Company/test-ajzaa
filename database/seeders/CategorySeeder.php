<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Assuming locale IDs: 1 for English, 2 for Arabic
        $enLocaleId = 1;
        $arLocaleId = 2;

        $now = Carbon::now();

        $categories = [
            [
                "name" => "Car Parts",
                "name_ar" => "قطع غيار السيارات",
                "order_prefix" => "CP",
                "subCategory" => [
                    [
                        "name" => "Oil Filters",
                        "id" => "9524",
                        "visible" => true,
                        "name_ar" => "فلاتر زيت"
                    ],
                    [
                        "name" => "Oils",
                        "id" => "7442",
                        "visible" => true,
                        "name_ar" => "زيوت"
                    ],
                    [
                        "name" => "A\\C & Heating",
                        "id" => "7405",
                        "visible" => true,
                        "name_ar" => "تكييف وتدفئة"
                    ],
                    [
                        "name" => "Brakes",
                        "id" => "7407",
                        "visible" => true,
                        "name_ar" => "فرامل"
                    ],
                    [
                        "name" => "Electrical",
                        "id" => "7409",
                        "visible" => true,
                        "name_ar" => "كهرباء"
                    ],
                    [
                        "name" => "Engine",
                        "id" => "7424",
                        "visible" => true,
                        "name_ar" => "محرك"
                    ],
                    [
                        "name" => "External & Internal Body",
                        "id" => "7433",
                        "visible" => true,
                        "name_ar" => "هيكل خارجي وداخلي"
                    ],
                    [
                        "name" => "Factory Wheel",
                        "id" => "7437",
                        "visible" => true,
                        "name_ar" => "جنوط"
                    ],
                    [
                        "name" => "Injection",
                        "id" => "7439",
                        "visible" => true,
                        "name_ar" => "حقن"
                    ],
                    [
                        "name" => "Suspension & Handling",
                        "id" => "7443",
                        "visible" => true,
                        "name_ar" => "تعليق وتوجيه"
                    ],
                    [
                        "name" => "Transmission & Differential Parts",
                        "id" => "7447",
                        "visible" => true,
                        "name_ar" => "ناقل الحركة والدفرنس"
                    ],
                    [
                        "name" => "Engine Cooling",
                        "id" => "7431",
                        "visible" => true,
                        "name_ar" => "تبريد المحرك"
                    ]
                ]
            ],
            [
                "name" => "Oils and filters",
                "name_ar" => "الزيوت والفلاتر",
                "order_prefix" => "OF"
            ],
            [
                "name" => "Car Covers",
                "name_ar" => "كفرات السيارات",
                "order_prefix" => "CC"
            ],
            [
                "name" => "Decorations and accessories",
                "name_ar" => "الزينة والاكسسوارات",
                "order_prefix" => "DA"
            ]
        ];

        foreach ($categories as $category) {
            // Insert parent category
            $categoryId = DB::table('categories')->insertGetId([
                'order_prefix' => $category['order_prefix'],
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

            // Insert subcategories if they exist
            if (isset($category['subCategory'])) {
                foreach ($category['subCategory'] as $subCategory) {
                    // Insert subcategory
                    $subCategoryId = DB::table('categories')->insertGetId([
                        'parent_id' => $categoryId,
                        'external_id' => $subCategory['id'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    // Insert subcategory locales
                    DB::table('category_locales')->insert([
                        [
                            'category_id' => $subCategoryId,
                            'locale_id' => $enLocaleId,
                            'name' => $subCategory['name'],
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                        [
                            'category_id' => $subCategoryId,
                            'locale_id' => $arLocaleId,
                            'name' => $subCategory['name_ar'],
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    ]);
                }
            }
        }
    }
}
