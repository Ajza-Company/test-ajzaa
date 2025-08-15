<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'country_id' => Country::inRandomOrder()->first()->id ?? 1,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'logo' => 'https://image.winudf.com/v2/image1/Y29tLnFhcmF3aS5xYXJhd2lhcHBfaWNvbl8xNTkyMTE1MDE3XzAwMQ/icon.png?w=184&fakeurl=1',
            'cover_image' => 'https://ich.ma/ar7/wp-content/uploads/2024/10/AF1QipOTdsULmmsfLkupmIrBP1L8y-8LekcgzgOeOdvIw426-h240-k-no.jpeg',
            'commercial_register' => $this->faker->randomNumber(5),
            'vat_number' => $this->faker->randomNumber(2),
            'commercial_register_file' => $this->faker->filePath()
        ];
    }
}
