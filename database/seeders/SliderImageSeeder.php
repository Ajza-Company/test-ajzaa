<?php

namespace Database\Seeders;

use App\Models\SliderImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SliderImage::factory()->count(10)->create();
    }
}
