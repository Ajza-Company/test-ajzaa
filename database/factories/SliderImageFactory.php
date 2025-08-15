<?php

namespace Database\Factories;

use App\Models\Locale;
use App\Models\SliderImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SliderImageFactory extends Factory
{
    protected $model = SliderImage::class;

    public function definition(): array
    {
        $locale = Locale::inRandomOrder()->first();

        return [
            'locale_id' => $locale->id ?? 1,
            'image' => randomImage('slider')[rand(0, 3)],
            'order' => $this->faker->randomNumber(),
            'is_active' => $this->faker->boolean()
        ];
    }
}
