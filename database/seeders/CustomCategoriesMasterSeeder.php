<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomCategoriesMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Custom Categories Test Data Seeding...');

        // Run migrations first (ensure database structure is ready)
        $this->command->info('📊 Running migrations...');
        \Illuminate\Support\Facades\Artisan::call('migrate');

        // Seed test users
        $this->command->info('👥 Creating test users...');
        $this->call(TestUsersSeeder::class);

        // Seed custom categories and products
        $this->command->info('🏷️ Creating custom categories and products...');
        $this->call(CustomCategoriesTestSeeder::class);

        $this->command->info('✅ Custom Categories Test Data Seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📋 Test Data Summary:');
        $this->command->info('   • 1 Test Company (ID: 1)');
        $this->command->info('   • 4 System Categories');
        $this->command->info('   • 5 Custom Categories');
        $this->command->info('   • 5 Test Products');
        $this->command->info('   • 3 Test Users');
        $this->command->info('');
        $this->command->info('🔑 Test Users Credentials:');
        $this->command->info('   • System Admin: admin@test.com / password123');
        $this->command->info('   • Company Admin: company@test.com / password123');
        $this->command->info('   • Regular User: user@test.com / password123');
        $this->command->info('');
        $this->command->info('🌐 API Testing:');
        $this->command->info('   • Base URL: http://localhost:8000/api/v1');
        $this->command->info('   • Company ID: 1');
        $this->command->info('   • Use Postman collection for testing');
    }
}
