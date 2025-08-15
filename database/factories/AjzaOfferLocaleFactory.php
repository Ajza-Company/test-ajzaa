<?php

namespace Database\Factories;

use App\Models\AjzaOffer;
use App\Models\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AjzaOfferLocale>
 */
class AjzaOfferLocaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locale_id = Locale::inRandomOrder()->first()->id ?? 1;

        return [
            'locale_id' => $locale_id,
            'ajza_offer_id' => AjzaOffer::inRandomOrder()->first()->id ?? 1,
            'title' => fake()->word(),
            'description' => fake()->sentence()
        ];
    }
}
