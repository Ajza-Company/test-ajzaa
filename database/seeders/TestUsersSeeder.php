<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create normal active clients
        User::create([
            'name' => 'Ahmed Client',
            'email' => 'ahmed@test.com',
            'full_mobile' => '+966501234567',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_registered' => true,
            'deletion_status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sara Client',
            'email' => 'sara@test.com',
            'full_mobile' => '+966501234568',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_registered' => true,
            'deletion_status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create users with pending deletion
        User::create([
            'name' => 'Omar Pending',
            'email' => 'omar@test.com',
            'full_mobile' => '+966501234569',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_registered' => true,
            'deletion_status' => 'pending_deletion',
            'deletion_requested_at' => now()->subDays(5),
            'deletion_reason' => 'No longer using the app',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Fatima Pending',
            'email' => 'fatima@test.com',
            'full_mobile' => '+966501234570',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_registered' => true,
            'deletion_status' => 'pending_deletion',
            'deletion_requested_at' => now()->subDays(10),
            'deletion_reason' => 'Moving to another country',
            'email_verified_at' => now(),
        ]);

        // Create users that should be automatically deleted (over 15 days)
        User::create([
            'name' => 'Khalid Old Pending',
            'email' => 'khalid@test.com',
            'full_mobile' => '+966501234571',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_registered' => true,
            'deletion_status' => 'pending_deletion',
            'deletion_requested_at' => now()->subDays(20),
            'deletion_reason' => 'Old deletion request',
            'email_verified_at' => now(),
        ]);

        // Create already deleted users
        User::create([
            'name' => 'Yusuf Deleted',
            'email' => 'yusuf@test.com',
            'full_mobile' => '+966501234572',
            'password' => Hash::make('password'),
            'is_active' => false,
            'is_registered' => true,
            'deletion_status' => 'deleted',
            'deletion_requested_at' => now()->subDays(25),
            'deletion_reason' => 'Already processed deletion',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Test users created successfully!');
        $this->command->info('Active users: 2');
        $this->command->info('Pending deletion: 2');
        $this->command->info('Should be auto-deleted: 1');
        $this->command->info('Already deleted: 1');
    }
}
