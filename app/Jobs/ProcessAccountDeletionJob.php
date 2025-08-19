<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessAccountDeletionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $usersToDeactivate = User::where('deletion_status', 'pending_deletion')
            ->where('deletion_requested_at', '<=', now()->subDays(15))
            ->get();
        
        foreach ($usersToDeactivate as $user) {
            DB::transaction(function () use ($user) {
                // Update user status to deleted (but keep in database)
                $user->update([
                    'deletion_status' => 'deleted',
                    'is_active' => false
                ]);
                
                // User remains in database but cannot:
                // - Make orders
                // - Access protected features
                // - Login (handled by middleware)
            });
        }
    }
}
