<?php

namespace Database\Factories;

use App\Models\Locale;
use App\Models\Store;
use App\Models\StoreLocale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StoreLocaleFactory extends Factory
{
    protected $model = StoreLocale::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::inRandomOrder()->first()->id ?? 1,
            'locale_id' => Locale::inRandomOrder()->first()->id ?? 1,
            'name' => fake()->unique()->name()
        ];
    }
}
