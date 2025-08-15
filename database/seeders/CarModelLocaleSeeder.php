<?php

namespace Database\Seeders;

use App\Models\CarModelLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarModelLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarModelLocale::factory()->count(30)->create();
    }
}
