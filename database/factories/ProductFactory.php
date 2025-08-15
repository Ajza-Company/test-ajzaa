<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'part_number' => $this->faker->word(),
            'image' => randomImage()[rand(0, 10)],
            'price' => $this->faker->randomFloat(2, 0, 1000)
        ];
    }
}
