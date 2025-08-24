<?php

namespace Database\Seeders;

use App\Models\RepChat;
use App\Models\RepOrder;
use App\Models\User;
use Illuminate\Database\Seeder;

class RepChatSeeder extends Seeder
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

        // Get rep-orders for the test user
        $repOrders = RepOrder::where('user_id', $testUser->id)->get();
        
        if ($repOrders->isEmpty()) {
            $this->command->error('No rep-orders found for test user. Please run RepOrderSeeder first.');
            return;
        }

        // Get another user to be the representative
        $representative = User::where('id', '!=', $testUser->id)->first();
        
        if (!$representative) {
            $this->command->error('No other users found to create chats with.');
            return;
        }

        foreach ($repOrders as $repOrder) {
            // Create chat for each rep-order
            RepChat::create([
                'rep_order_id' => $repOrder->id,
                'user1_id' => $testUser->id,        // Customer
                'user2_id' => $representative->id   // Representative
            ]);
        }

        $this->command->info('✅ Created ' . $repOrders->count() . ' rep-chats for test user: ' . $testUser->email);
    }
}
