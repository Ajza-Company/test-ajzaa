<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = json_decode(File::get("database/data/brands.json"));

        foreach ($brands as $value) {
            // Create Car Brand
            $carBrand = CarBrand::create([
                'external_id' => $value->id,
                "is_active" => $value->include_in_menu
            ]);

            // Create Brand Locales
            $carBrand->locales()->create([
                'locale_id' => 1,
                "name" => $value->name
            ]);

            $carBrand->locales()->create([
                'locale_id' => 2,
                "name" => $value->name_ar
            ]);

            // Fetch Car Models for this brand
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://api.rafraf.com/graphql', [
                'query' => 'query getCategories($parent: String!) {
                categories(filters: {ids: {eq: $parent}}) {
                    items {
                        name
                        id
                        children {
                            name
                            id
                        }
                    }
                }
            }',
                'variables' => [
                    'parent' => $value->id
                ]
            ]);

            if ($response->successful()) {
                $models = $response->json()['data']['categories']['items'][0]['children'] ?? [];

                // Create Car Models
                foreach ($models as $model) {
                    $carModel = CarModel::create([
                        'external_id' => $model['id'],
                        'car_brand_id' => $carBrand->id
                    ]);

                    // Create Model Locales
                    $carModel->locales()->create([
                        'locale_id' => 1,
                        'name' => $model['name']
                    ]);

                    $carModel->locales()->create([
                        'locale_id' => 2,
                        'name' => $model['name'] // You'll need to implement this method
                    ]);
                }
            }
        }
    }
}
