<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:cleanup {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old categories and prepare for flat structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will delete ALL existing categories and their locales. Are you sure?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting categories cleanup...');

        try {
            // Count existing categories
            $categoryCount = DB::table('categories')->count();
            $localeCount = DB::table('category_locales')->count();

            $this->info("Found {$categoryCount} categories and {$localeCount} category locales");

            // Delete all category locales first (foreign key constraint)
            DB::table('category_locales')->delete();
            $this->info('✓ Deleted all category locales');

            // Delete all categories
            DB::table('categories')->delete();
            $this->info('✓ Deleted all categories');

            $this->info('Categories cleanup completed successfully!');
            $this->info('Now you can run: php artisan db:seed --class=FlatCategoriesSeeder');

        } catch (\Exception $e) {
            $this->error('Error during cleanup: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
