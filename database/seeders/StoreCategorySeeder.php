<?php

namespace Database\Seeders;

use App\Models\StoreCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreCategory::factory()->count(100)->create();
    }
}
