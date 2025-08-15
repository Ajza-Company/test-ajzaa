<?php

namespace Database\Factories;

use App\Models\AjzaOffer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AjzaOfferFactory extends Factory
{
    protected $model = AjzaOffer::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::inRandomOrder()->first()->id ?? 1,
            'price' => $this->faker->randomFloat(2, 0, 9999.99),
            'old_price' => function (array $attributes) {
                // Ensure old_price is always higher than price
                return $this->faker->randomFloat(2, $attributes['price'], min($attributes['price'] * 1.5, 9999.99));
            },
            'image' => randomImage()[rand(0, 10)]
        ];
    }
}
