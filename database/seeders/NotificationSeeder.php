<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\OrderNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => 1, // Assuming user ID 1
                'data' => json_encode([
                    'title' => __('notifications.order_confirmed.title', [], 'en'),
                    'description' => __('notifications.order_confirmed.description', [], 'en'),
                    'icon' => $this->getIcon('order_confirmed'),
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\OrderNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => 1, // Assuming user ID 1
                'data' => json_encode([
                    'title' => __('notifications.discount_code.title', [], 'en'),
                    'description' => __('notifications.discount_code.description', [], 'en'),
                    'icon' => $this->getIcon('discount_code'),
                ]),
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\OrderNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => 2, // Assuming user ID 2
                'data' => json_encode([
                    'title' => __('notifications.app_update.title', [], 'ar'),
                    'description' => __('notifications.app_update.description', [], 'ar'),
                    'icon' => $this->getIcon('app_update'),
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('notifications')->insert($notifications);
    }

    private function getIcon($type): string
    {
        return [
            'order_confirmed' => 'check-circle',
            'discount_code' => 'tag',
            'order_shipped' => 'truck',
            'app_update' => 'sync-alt',
        ][$type];
    }
}
