<?php

namespace Database\Seeders;

use App\Models\ProductLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductLocale::factory()->count(400)->create();
    }
}
