<?php

namespace Database\Seeders;

use App\Models\CarTypeLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarTypeLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarTypeLocale::factory()->count(7)->create();
    }
}
