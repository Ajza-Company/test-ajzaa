<?php

namespace Database\Factories;

use App\Models\CarType;
use App\Models\CarTypeLocale;
use App\Models\Locale;
use Faker\Provider\FakeCar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CarTypeLocaleFactory extends Factory
{
    protected $model = CarTypeLocale::class;

    public function definition(): array
    {
        fake()->addProvider(new FakeCar(fake()));

        do {
            $localeId = Locale::inRandomOrder()->first()->id ?? 1;
            $carTypeId = CarType::inRandomOrder()->first()->id ?? 1;
            $name = fake()->unique()->vehicleType;

            $exists = CarTypeLocale::where('locale_id', $localeId)
                ->where('car_type_id', $carTypeId)
                ->where('name', $name)
                ->exists();
        } while ($exists);

        return [
            'car_type_id' => $carTypeId,
            'locale_id' => $localeId,
            'name' => $name
        ];
    }
}
