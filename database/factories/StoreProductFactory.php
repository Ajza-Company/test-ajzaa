<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StoreProductFactory extends Factory
{
    protected $model = StoreProduct::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::inRandomOrder()->first()->id ?? 1,
            'product_id' => Product::inRandomOrder()->first()->id ?? 1,
            'price' => $this->faker->randomFloat(2, 0, 1000)
        ];
    }
}
