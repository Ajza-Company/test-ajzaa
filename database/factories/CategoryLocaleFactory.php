<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\CategoryLocale;
use App\Models\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CategoryLocaleFactory extends Factory
{
    protected $model = CategoryLocale::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'locale_id' => Locale::inRandomOrder()->first()->id ?? 1,
            'name' => fake()->unique()->name()
        ];
    }
}
