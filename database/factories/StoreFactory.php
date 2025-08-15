<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Company;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id ?? 1,
            'area_id' => Area::inRandomOrder()->first()->id ?? 1,
            'image' => randomImage()[rand(0, 10)],
            'address' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude()
        ];
    }
}
