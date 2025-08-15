<?php

namespace Database\Factories;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarType;
use App\Models\Product;
use App\Models\ProductCarAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProductCarAttributeFactory extends Factory
{
    protected $model = ProductCarAttribute::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? 1,
            'car_brand_id' => CarBrand::inRandomOrder()->first()->id ?? 1,
            'car_model_id' => CarModel::inRandomOrder()->first()->id ?? 1,
            'car_type_id' => CarType::inRandomOrder()->first()->id ?? 1
        ];
    }
}
