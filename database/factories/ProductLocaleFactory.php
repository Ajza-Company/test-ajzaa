<?php

namespace Database\Factories;

use App\Models\Locale;
use App\Models\Product;
use App\Models\ProductLocale;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductLocaleFactory extends Factory
{
    protected $model = ProductLocale::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? 1,
            'locale_id' => Locale::inRandomOrder()->first()->id ?? 1,
            'name' => $this->faker->name(),
            'description' => $this->faker->randomHtml(2, 3)
        ];
    }
}
