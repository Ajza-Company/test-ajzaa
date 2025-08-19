<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ActivateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:activate {mobile : User mobile number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate a user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mobile = $this->argument('mobile');
        
        $user = User::where('full_mobile', $mobile)->first();
        
        if (!$user) {
            $this->error("User with mobile {$mobile} not found!");
            return 1;
        }

        $this->info("User Information:");
        $this->line("ID: {$user->id}");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Mobile: {$user->full_mobile}");
        $this->line("Current Status:");
        $this->line("  - Is Active: " . ($user->is_active ? 'Yes' : 'No'));
        $this->line("  - Is Registered: " . ($user->is_registered ? 'Yes' : 'No'));
        $this->line("  - Deletion Status: {$user->deletion_status}");
        
        $this->newLine();
        
        // Check if user needs activation
        if ($user->is_active && $user->is_registered && $user->deletion_status === 'active') {
            $this->info("✅ User is already active and can login!");
            return 0;
        }
        
        // Activate user
        $updates = [];
        
        if (!$user->is_active) {
            $updates[] = 'is_active = true';
            $user->is_active = true;
        }
        
        if (!$user->is_registered) {
            $updates[] = 'is_registered = true';
            $user->is_registered = true;
        }
        
        if ($user->deletion_status !== 'active') {
            $updates[] = 'deletion_status = active';
            $user->deletion_status = 'active';
            $user->deletion_requested_at = null;
            $user->deletion_reason = null;
        }
        
        $user->save();
        
        $this->info("✅ User activated successfully!");
        $this->line("Updated fields: " . implode(', ', $updates));
        
        $this->newLine();
        $this->info("User Status After Activation:");
        $this->line("  - Is Active: " . ($user->is_active ? 'Yes' : 'No'));
        $this->line("  - Is Registered: " . ($user->is_registered ? 'Yes' : 'No'));
        $this->line("  - Deletion Status: {$user->deletion_status}");
        
        $this->newLine();
        $this->info("✅ User can now login successfully!");
        
        return 0;
    }
}
