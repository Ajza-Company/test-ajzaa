<?php

namespace Database\Seeders;

use App\Models\CompanyLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyLocaleSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyLocale::factory()->count(100)->create();
    }
}
