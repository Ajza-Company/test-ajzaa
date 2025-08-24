<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductLocale;
use App\Models\Locale;
use Illuminate\Database\Seeder;

class ProductLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get locale IDs
        $enLocale = Locale::where('locale', 'en')->first();
        $arLocale = Locale::where('locale', 'ar')->first();
        
        if (!$enLocale || !$arLocale) {
            $this->command->error('Locales not found. Please run LocaleSeeder first.');
            return;
        }

        // Get all products
        $products = Product::all();
        
        foreach ($products as $product) {
            // Create English locale
            ProductLocale::create([
                'product_id' => $product->id,
                'locale_id' => $enLocale->id,
                'name' => fake()->name(),
                'description' => fake()->randomHtml(2, 3)
            ]);
            
            // Create Arabic locale with Arabic names
            ProductLocale::create([
                'product_id' => $product->id,
                'locale_id' => $arLocale->id,
                'name' => 'منتج ' . $product->id, // Arabic prefix + product ID
                'description' => 'وصف المنتج رقم ' . $product->id . ' باللغة العربية'
            ]);
        }
        
        $this->command->info('Created localized names for ' . $products->count() . ' products in both English and Arabic.');
    }
}
