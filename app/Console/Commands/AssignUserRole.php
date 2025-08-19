<?php

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-role {mobile : User mobile number} {role : Role name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mobile = $this->argument('mobile');
        $roleName = $this->argument('role');
        
        // Find user
        $user = User::where('full_mobile', $mobile)->first();
        
        if (!$user) {
            $this->error("User with mobile {$mobile} not found!");
            return 1;
        }

        // Find role
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            $this->error("Role '{$roleName}' not found!");
            $this->line("Available roles:");
            $availableRoles = Role::all();
            foreach ($availableRoles as $availableRole) {
                $this->line("- {$availableRole->name}");
            }
            return 1;
        }

        // Check if user already has this role
        if ($user->hasRole($roleName)) {
            $this->warn("User already has role '{$roleName}'");
            return 0;
        }

        // Assign role
        $user->assignRole($role);
        
        $this->info("✅ Role '{$roleName}' assigned to user {$user->name} successfully!");
        
        // Show updated user roles
        $this->newLine();
        $this->info("User roles after assignment:");
        $userRoles = $user->fresh()->roles;
        foreach ($userRoles as $userRole) {
            $this->line("- {$userRole->name}");
        }
        
        return 0;
    }
}
