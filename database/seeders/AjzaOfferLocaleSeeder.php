<?php

namespace Database\Seeders;

use App\Models\AjzaOfferLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AjzaOfferLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AjzaOfferLocale::factory()->count(30)->create();
    }
}
