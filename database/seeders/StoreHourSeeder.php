<?php

namespace Database\Seeders;

use App\Models\Locale;
use App\Models\Store;
use App\Models\StoreHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StoreHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hours = json_decode(File::get("database/data/storeHours.json"));

        foreach (Store::select('id')->get() as $store) {
            foreach ($hours as $value) {
                StoreHour::create([
                    "store_id" => $store->id,
                    "day" => $value->day,
                    "open_time" => $value->open_time,
                    "close_time" => $value->close_time
                ]);
            }
        }
    }
}
