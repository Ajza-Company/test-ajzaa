<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Store;
use App\Models\StoreCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreCategoryFactory extends Factory
{
    protected $model = StoreCategory::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::inRandomOrder()->first()->id ?? 1,
            'category_id' => Category::inRandomOrder()->first()->id ?? 1
        ];
    }
}
