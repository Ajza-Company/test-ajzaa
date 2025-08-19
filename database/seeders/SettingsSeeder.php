<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'app_name',
                'value' => 'Ajza App',
                'type' => 'string',
                'is_active' => true
            ],
            [
                'key' => 'app_description',
                'value' => 'Your trusted automotive marketplace',
                'type' => 'string',
                'is_active' => true
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@ajza.net',
                'type' => 'string',
                'is_active' => true
            ],
            [
                'key' => 'contact_phone',
                'value' => '+966500000000',
                'type' => 'string',
                'is_active' => true
            ],
            [
                'key' => 'maintenance_mode',
                'value' => false,
                'type' => 'boolean',
                'is_active' => true
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
