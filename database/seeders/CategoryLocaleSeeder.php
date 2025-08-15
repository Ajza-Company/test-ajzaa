<?php

namespace Database\Seeders;

use App\Models\CategoryLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryLocale::factory()->count(12)->create();
    }
}
