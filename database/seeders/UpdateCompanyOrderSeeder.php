<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class UpdateCompanyOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies and update their order
        $companies = Company::orderBy('id')->get();
        
        foreach ($companies as $index => $company) {
            $company->update(['order' => $index + 1]);
        }

        $this->command->info('✅ Updated order for ' . $companies->count() . ' companies');
    }
}
