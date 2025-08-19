<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AjzaOfferLocale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            LocaleSeeder::class,
            UserSeeder::class,
            // CompanySeeder::class,
            // CompanyLocaleSeeder::class,
            CarBrandSeeder::class,
            // CarModelSeeder::class,
            // CarModelLocaleSeeder::class,
            // CarTypeSeeder::class,
            // CarTypeLocaleSeeder::class,
            FlatCategoriesSeeder::class,
            // CategoryLocaleSeeder::class,
            CountrySeeder::class,
            // StoreSeeder::class,
            // StoreLocaleSeeder::class,
            // StoreCategorySeeder::class,
//            ProductSeeder::class,
//            ProductLocaleSeeder::class,
            // StoreProductSeeder::class,
            // SliderImageSeeder::class,
            // NotificationSeeder::class,
            // AjzaOfferSeeder::class,
            // AjzaOfferLocaleSeeder::class,
            // ProductCarAttributeSeeder::class,
            // StoreHourSeeder::class
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
