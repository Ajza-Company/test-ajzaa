<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\State;
use App\Models\Area;
use Illuminate\Database\Seeder;

class TestCategory32IssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔍 Testing Category 32 Stores Issue...');

        // Check if category 32 exists
        $category32 = Category::find(32);
        if (!$category32) {
            $this->command->error('❌ Category 32 does not exist!');
            return;
        }

        $this->command->info("✅ Category 32 found: " . ($category32->localized ? $category32->localized->name : 'No name'));

        // Check stores in category 32
        $storeCategories = StoreCategory::where('category_id', 32)->get();
        $this->command->info("📊 Found {$storeCategories->count()} store-category relationships");

        foreach ($storeCategories as $storeCategory) {
            $store = Store::with(['company', 'area'])->find($storeCategory->store_id);
            
            if ($store) {
                $this->command->info("🏪 Store ID {$store->id}:");
                $this->command->info("   - Company ID: {$store->company_id} (Active: " . ($store->company && $store->company->is_active ? 'Yes' : 'No') . ")");
                $this->command->info("   - Store Active: " . ($store->is_active ? 'Yes' : 'No'));
                $this->command->info("   - Area ID: {$store->area_id}");
                
                // Check if store would pass the filters in the controller
                $passesCompanyFilter = $store->company && $store->company->is_active;
                $passesStoreFilter = $store->is_active;
                
                $this->command->info("   - Passes Company Filter: " . ($passesCompanyFilter ? '✅ Yes' : '❌ No'));
                $this->command->info("   - Passes Store Filter: " . ($passesStoreFilter ? '✅ Yes' : '❌ No'));
                $this->command->info("   - Would Show in API: " . ($passesCompanyFilter && $passesStoreFilter ? '✅ Yes' : '❌ No'));
            } else {
                $this->command->error("❌ Store ID {$storeCategory->store_id} not found!");
            }
        }

        // Test the actual query that the controller uses
        $this->command->info("\n🧪 Testing Controller Query Logic:");
        
        $storesQuery = $category32->stores()
            ->whereHas('company', function ($query) {
                $query->where('is_active', true);
            })
            ->where('is_active', true);

        $this->command->info("   - Raw SQL: " . $storesQuery->toSql());
        $this->command->info("   - Bindings: " . json_encode($storesQuery->getBindings()));
        
        $stores = $storesQuery->get();
        $this->command->info("   - Stores returned: {$stores->count()}");
        
        foreach ($stores as $store) {
            $this->command->info("   - Store {$store->id}: " . ($store->company ? $store->company->name : 'No name'));
        }

        $this->command->info("\n🎯 Summary:");
        $this->command->info("   - Total relationships: {$storeCategories->count()}");
        $this->command->info("   - Stores passing filters: {$stores->count()}");
        $this->command->info("   - This explains why stores_count = {$storeCategories->count()} but stores = []");
    }
}
