<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test company if it doesn't exist
        $company = Company::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Test Company',
                'email' => 'test@company.com',
                'phone' => '+966501234567',
                'is_active' => true,
                'is_approved' => true
            ]
        );

        // Create System Admin User
        $systemAdmin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'System Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+966501234568',
                'is_active' => true,
                'is_admin' => true,
                'company_id' => null
            ]
        );

        // Create Company Admin User
        $companyAdmin = User::firstOrCreate(
            ['email' => 'company@test.com'],
            [
                'name' => 'Company Admin',
                'email' => 'company@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+966501234569',
                'is_active' => true,
                'is_admin' => false,
                'company_id' => $company->id
            ]
        );

        // Create Regular User
        $regularUser = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@test.com',
                'password' => Hash::make('password123'),
                'phone' => '+966501234570',
                'is_active' => true,
                'is_admin' => false,
                'company_id' => $company->id
            ]
        );

        $this->command->info('Test users created successfully!');
        $this->command->info('System Admin: admin@test.com / password123');
        $this->command->info('Company Admin: company@test.com / password123');
        $this->command->info('Regular User: user@test.com / password123');
    }
}
