<?php

namespace Database\Seeders;

use App\Models\RepOrder;
use App\Models\User;
use App\Models\Address;
use App\Models\State;
use App\Enums\RepOrderStatusEnum;
use Illuminate\Database\Seeder;

class RepOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user
        $testUser = User::where('email', 'test@supplier.com')->first();
        
        if (!$testUser) {
            $this->command->error('Test user not found. Please run GenerateTestToken command first.');
            return;
        }

        // Get or create a state
        $state = State::first() ?? State::create([
            'name' => 'Riyadh',
            'country_id' => 1
        ]);

        // Get or create an address
        $address = Address::first() ?? Address::create([
            'user_id' => $testUser->id,
            'state_id' => $state->id,
            'area_id' => 1,
            'street' => 'Test Street',
            'building_number' => '123',
            'floor' => '1',
            'apartment' => 'A1',
            'postal_code' => '12345',
            'is_default' => true
        ]);

        // Create rep-orders for the test user
        $repOrders = [
            [
                'user_id' => $testUser->id,
                'title' => 'طلب تشليح سيارة تويوتا',
                'description' => 'أريد تشليح سيارة تويوتا كامري موديل 2020، اللون أبيض، المحرك يعمل بشكل جيد',
                'image' => 'default-no-image.jpg',
                'state_id' => $state->id,
                'address_id' => $address->id,
                'status' => RepOrderStatusEnum::PENDING
            ],
            [
                'user_id' => $testUser->id,
                'title' => 'طلب تشليح سيارة هوندا',
                'description' => 'أحتاج تشليح سيارة هوندا سيفيك موديل 2019، اللون أسود، المحرك يحتاج صيانة',
                'image' => 'default-no-image.jpg',
                'state_id' => $state->id,
                'address_id' => $address->id,
                'status' => RepOrderStatusEnum::ACCEPTED
            ],
            [
                'user_id' => $testUser->id,
                'title' => 'طلب تشليح سيارة نيسان',
                'description' => 'أريد تشليح سيارة نيسان صني موديل 2018، اللون أحمر، المحرك يعمل بشكل ممتاز',
                'image' => 'default-no-image.jpg',
                'state_id' => $state->id,
                'address_id' => $address->id,
                'status' => RepOrderStatusEnum::PENDING
            ],
            [
                'user_id' => $testUser->id,
                'title' => 'طلب تشليح سيارة فورد',
                'description' => 'أحتاج تشليح سيارة فورد فوكس موديل 2021، اللون أزرق، المحرك جديد',
                'image' => 'default-no-image.jpg',
                'state_id' => $state->id,
                'address_id' => $address->id,
                'status' => RepOrderStatusEnum::CANCELLED
            ],
            [
                'user_id' => $testUser->id,
                'title' => 'طلب تشليح سيارة شيفروليه',
                'description' => 'أريد تشليح سيارة شيفروليه كابتيفا موديل 2017، اللون رمادي، المحرك يحتاج إصلاح',
                'image' => 'default-no-image.jpg',
                'state_id' => $state->id,
                'address_id' => $address->id,
                'status' => RepOrderStatusEnum::ENDED
            ]
        ];

        foreach ($repOrders as $repOrderData) {
            RepOrder::create($repOrderData);
        }

        $this->command->info('✅ Created ' . count($repOrders) . ' rep-orders for test user: ' . $testUser->email);
    }
}
