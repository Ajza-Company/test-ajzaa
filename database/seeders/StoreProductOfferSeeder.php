<?php

namespace Database\Seeders;

use App\Models\StoreLocale;
use App\Models\StoreProductOffer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreProductOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreProductOffer::factory()->count(30)->create();
    }
}
