<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\AreaLocale;
use App\Models\Country;
use App\Models\CountryLocale;
use App\Models\Locale;
use App\Models\State;
use App\Models\StateLocale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('max_execution_time', '3600');
        // Read the JSON file
        $countries_json = json_decode(File::get('database/data/countries/countries.json'), true);
        $states_json = json_decode(File::get('database/data/countries/states.json'), true);
        $district_json = json_decode(File::get('database/data/countries/cities.json'), true);

        foreach ($countries_json as $country)
        {
            $locale = Locale::inRandomOrder()->first()->id ?? 1;
            $states = $this->getStates($states_json, $country['id']);
            if (count($states) > 0) {
                $createdCountry = Country::create([
                    'iso2' => $country['iso2'],
                    'phone_code' => $country['phone_code'],
                    'latitude' => $country['latitude'],
                    'longitude' => $country['longitude'],
                    'numeric_code' => $country['numeric_code'],
                    'emoji' => $country['emoji']
                ]);

                CountryLocale::create([
                    'country_id' => $createdCountry->id,
                    'locale_id' => $locale,
                    'name' => $country['name'],
                    'currency_code' => $country['currency']
                ]);
                foreach ($states as $state) {
                    $createdState = State::create([
                        'country_id' => $createdCountry->id,
                        'longitude' => $state['longitude'],
                        'latitude' => $state['latitude']
                    ]);

                    StateLocale::create([
                       'state_id' => $createdState->id,
                       'locale_id' => $locale,
                       'name' => $state['name']
                    ]);
                    $districts = $this->getDistricts($district_json, $state['id']);
                    foreach ($districts as $district) {
                        $createdDistrict = Area::create([
                            'state_id' => $createdState->id,
                            'longitude' => $district['longitude'],
                            'latitude' => $district['latitude']
                        ]);

                        AreaLocale::create([
                           'area_id' => $createdDistrict->id,
                           'locale_id' => $locale,
                           'name' => $district['name']
                        ]);
                    }
                }
            }

        }
    }

    public function getStates($states_json, $value): array
    {
        return array_filter($states_json, function ($item) use ($value){
            return $item['country_id'] === $value;
        });
    }

    public function getDistricts($json, $value): array
    {
        return array_filter($json, function ($item) use ($value){
            return $item['state_id'] === $value;
        });
    }
}
