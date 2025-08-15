<?php

namespace Database\Seeders;

use App\Models\ProductCarAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCarAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCarAttribute::factory()->count(200)->create();
    }
}
