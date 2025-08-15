<?php

namespace Database\Seeders;

use App\Models\StoreLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreLocale::factory()->count(100)->create();
    }
}
