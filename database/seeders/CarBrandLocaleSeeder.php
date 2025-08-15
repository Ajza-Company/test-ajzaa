<?php

namespace Database\Seeders;

use App\Models\CarBrandLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarBrandLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarBrandLocale::factory()->count(30)->create();
    }
}
