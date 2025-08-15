<?php

namespace Database\Factories;

use App\Models\CarModel;
use App\Models\CarModelLocale;
use App\Models\Locale;
use Faker\Provider\FakeCar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends Factory<CarModelLocale>
 */
class CarModelLocaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locale = Locale::inRandomOrder()->first();
        $faker = Faker::create($locale->locale);
        $faker->addProvider(new FakeCar(fake()));

        return [
            'locale_id' => $locale->id ?? 1,
            'car_model_id' => CarModel::inRandomOrder()->first()->id ?? 1,
            'name' => $faker->unique()->vehicleModel,
        ];
    }
}
