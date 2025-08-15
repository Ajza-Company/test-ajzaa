<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyLocale;
use App\Models\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyLocaleFactory extends Factory
{
    protected $model = CompanyLocale::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id ?? 1,
            'locale_id' => Locale::inRandomOrder()->first()->id ?? 1,
            'name' => fake()->unique()->name()
        ];
    }
}
