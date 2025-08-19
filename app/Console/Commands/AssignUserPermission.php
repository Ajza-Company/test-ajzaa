<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class AssignUserPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-permission {mobile : User mobile number} {permission : Permission name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a permission to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mobile = $this->argument('mobile');
        $permissionName = $this->argument('permission');
        
        // Find user
        $user = User::where('full_mobile', $mobile)->first();
        
        if (!$user) {
            $this->error("User with mobile {$mobile} not found!");
            return 1;
        }

        // Find permission
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            $this->error("Permission '{$permissionName}' not found!");
            $this->line("Available permissions:");
            $availablePermissions = Permission::all();
            foreach ($availablePermissions as $availablePermission) {
                $this->line("- {$availablePermission->name} (Group: {$availablePermission->group_name})");
            }
            return 1;
        }

        // Check if user already has this permission
        if ($user->hasPermissionTo($permissionName)) {
            $this->warn("User already has permission '{$permissionName}'");
            return 0;
        }

        // Assign permission
        $user->givePermissionTo($permission);
        
        $this->info("✅ Permission '{$permissionName}' assigned to user {$user->name} successfully!");
        
        // Show updated user permissions
        $this->newLine();
        $this->info("User permissions after assignment:");
        $userPermissions = $user->fresh()->permissions;
        foreach ($userPermissions as $userPermission) {
            $this->line("- {$userPermission->name} (Group: {$userPermission->group_name})");
        }
        
        return 0;
    }
}
