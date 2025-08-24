<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UpdateUserAvatarSeeder extends Seeder
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

        // Update avatar with a real image URL
        $testUser->update([
            'avatar' => 'https://cdn.salla.sa/EqoGY/RezCc2coBV4vHkMlfockAETZ8AuPPiaJDXOBt50c.jpg'
        ]);

        $this->command->info('✅ Updated avatar for test user: ' . $testUser->email);
    }
}
