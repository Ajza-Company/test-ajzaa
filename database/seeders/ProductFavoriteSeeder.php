<?php

namespace Database\Seeders;

use App\Models\ProductFavorite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductFavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductFavorite::factory()->count(20)->create();
    }
}
