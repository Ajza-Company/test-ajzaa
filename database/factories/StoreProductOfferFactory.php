<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\StoreProductOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreProductOfferFactory extends Factory
{
    protected $model = StoreProductOffer::class;

    public function definition(): array
    {
        return [
            'store_product_id' => StoreProduct::inRandomOrder()->first()->id ?? 1,
            'type' => fake()->randomElement(['fixed', 'percentage']),
            'discount' => fake()->numberBetween(0, 100)
        ];
    }
}
