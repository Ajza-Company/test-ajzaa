<?php

namespace Database\Seeders;

use App\Models\AjzaOffer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AjzaOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AjzaOffer::factory()->count(30)->create();
    }
}
