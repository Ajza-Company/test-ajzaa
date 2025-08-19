<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-permissions {mobile : User mobile number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user permissions and roles';

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
        $this->line("Is Active: " . ($user->is_active ? 'Yes' : 'No'));
        $this->line("Is Registered: " . ($user->is_registered ? 'Yes' : 'No'));
        $this->line("Deletion Status: {$user->deletion_status}");
        
        $this->newLine();
        
        // Check roles
        if ($user->roles && $user->roles->count() > 0) {
            $this->info("Roles:");
            foreach ($user->roles as $role) {
                $this->line("- {$role->name}");
            }
        } else {
            $this->warn("User has no roles assigned!");
        }
        
        $this->newLine();
        
        // Check permissions
        if ($user->permissions && $user->permissions->count() > 0) {
            $this->info("Direct Permissions:");
            foreach ($user->permissions as $permission) {
                $this->line("- {$permission->name}");
            }
        } else {
            $this->warn("User has no direct permissions assigned!");
        }
        
        $this->newLine();
        
        // Check permissions through roles
        $rolePermissions = collect();
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if ($role->permissions) {
                    foreach ($role->permissions as $permission) {
                        $rolePermissions->push($permission);
                    }
                }
            }
        }
        
        if ($rolePermissions->count() > 0) {
            $this->info("Permissions through Roles:");
            $uniquePermissions = $rolePermissions->unique('name');
            foreach ($uniquePermissions as $permission) {
                $this->line("- {$permission->name}");
            }
        } else {
            $this->warn("User has no permissions through roles!");
        }
        
        $this->newLine();
        
        // Check if user can login
        if ($user->is_active && $user->is_registered && $user->deletion_status === 'active') {
            $this->info("✅ User can login successfully");
        } else {
            $this->error("❌ User cannot login due to:");
            if (!$user->is_active) $this->line("  - Account is not active");
            if (!$user->is_registered) $this->line("  - Account is not registered");
            if ($user->deletion_status !== 'active') $this->line("  - Deletion status: {$user->deletion_status}");
        }
        
        return 0;
    }
}
