<?php

namespace App\Console\Commands;

use App\Jobs\ProcessAccountDeletionJob;
use Illuminate\Console\Command;

class ProcessAccountDeletionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'accounts:process-deletions';

    /**
     * The console command description.
     */
    protected $description = 'Process account deletions after 15 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->confirm('This will process all pending account deletions. Continue?')) {
            ProcessAccountDeletionJob::dispatch();
            $this->info('Account deletion job dispatched successfully.');
            return 0;
        }
        
        $this->info('Operation cancelled.');
        return 1;
    }
}
