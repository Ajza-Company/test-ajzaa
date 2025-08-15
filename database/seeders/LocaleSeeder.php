<?php

namespace Database\Seeders;

use App\Models\Locale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = json_decode(File::get("database/data/locales.json"));

        foreach ($locales as $value) {
            Locale::create([
                "name" => $value->name,
                "locale" => $value->locale,
                'is_default' => $value->is_default
            ]);
        }
    }
}
