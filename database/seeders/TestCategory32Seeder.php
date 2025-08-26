<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\State;
use App\Models\Area;
use Illuminate\Database\Seeder;

class TestCategory32Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test company
        $company = Company::create([
            'id' => 999,
            'user_id' => 1, // Assuming user ID 1 exists
            'country_id' => 1, // Assuming Saudi Arabia exists
            'email' => 'test@category32.com',
            'phone' => '+966501234567',
            'cover_image' => 'test-cover.jpg',
            'logo' => 'test-logo.jpg',
            'is_approved' => true,
            'is_active' => true,
        ]);

        // Create company locale for the name
        $company->localized()->create([
            'locale' => 'ar',
            'name' => 'Test Company for Category 32',
        ]);

        // Create test state and area
        $state = State::firstOrCreate([
            'id' => 999,
            'name' => 'Test State',
            'country_id' => 1, // Assuming Saudi Arabia exists
        ]);

        $area = Area::firstOrCreate([
            'id' => 999,
            'name' => 'Test Area',
            'state_id' => $state->id,
        ]);

        // Create test store
        $store = Store::create([
            'id' => 999,
            'company_id' => $company->id,
            'area_id' => $area->id,
            'latitude' => 24.7136,
            'longitude' => 46.6753,
            'is_active' => true,
            'can_add_products' => true,
        ]);

        // Create category 32 if it doesn't exist
        $category32 = Category::firstOrCreate([
            'id' => 32,
            'is_active' => true,
            'sort_order' => 32,
        ]);

        // Create category locale
        $category32->localized()->create([
            'locale' => 'ar',
            'name' => 'زينة وإكسسوارات',
        ]);

        // Link store to category 32
        StoreCategory::create([
            'store_id' => $store->id,
            'category_id' => $category32->id,
        ]);

        // Create a few more stores for category 32 to test the limit
        for ($i = 1; $i <= 3; $i++) {
            $additionalStore = Store::create([
                'id' => 1000 + $i,
                'company_id' => $company->id,
                'area_id' => $area->id,
                'latitude' => 24.7136 + ($i * 0.001),
                'longitude' => 46.6753 + ($i * 0.001),
                'is_active' => true,
                'can_add_products' => true,
            ]);

            StoreCategory::create([
                'store_id' => $additionalStore->id,
                'category_id' => $category32->id,
            ]);
        }

        $this->command->info('✅ Test data created for Category 32:');
        $this->command->info("   - Company ID: {$company->id}");
        $this->command->info("   - Store IDs: {$store->id}, " . implode(', ', range(1001, 1003)));
        $this->command->info("   - Category ID: {$category32->id}");
        $this->command->info("   - Total stores in category 32: " . StoreCategory::where('category_id', 32)->count());
    }
}
