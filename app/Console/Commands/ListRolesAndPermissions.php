<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ListRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all roles and permissions in the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔐 ROLES AND PERMISSIONS SYSTEM");
        $this->line("=====================================");
        
        // List all roles
        $this->newLine();
        $this->info("📋 ROLES:");
        $this->line("----------");
        
        $roles = Role::with('permissions')->get();
        
        if ($roles->count() === 0) {
            $this->warn("No roles found in the system!");
        } else {
            foreach ($roles as $role) {
                $this->line("🎯 {$role->name}");
                $this->line("   ID: {$role->id}");
                $this->line("   Guard: {$role->guard_name}");
                $this->line("   Created: {$role->created_at->format('Y-m-d H:i:s')}");
                
                if ($role->permissions && $role->permissions->count() > 0) {
                    $this->line("   Permissions ({$role->permissions->count()}):");
                    foreach ($role->permissions as $permission) {
                        $this->line("     • {$permission->name} ({$permission->group_name})");
                    }
                } else {
                    $this->line("   Permissions: None");
                }
                $this->newLine();
            }
        }
        
        // List all permissions
        $this->info("🔑 PERMISSIONS:");
        $this->line("----------------");
        
        $permissions = Permission::orderBy('group_name')->orderBy('name')->get();
        
        if ($permissions->count() === 0) {
            $this->warn("No permissions found in the system!");
        } else {
            $groupedPermissions = $permissions->groupBy('group_name');
            
            foreach ($groupedPermissions as $groupName => $groupPermissions) {
                $this->line("📁 {$groupName} ({$groupPermissions->count()} permissions):");
                foreach ($groupPermissions as $permission) {
                    $this->line("   • {$permission->name}");
                    $this->line("     ID: {$permission->id} | Guard: {$permission->guard_name}");
                    if ($permission->friendly_name) {
                        $this->line("     Friendly Name: {$permission->friendly_name}");
                    }
                }
                $this->newLine();
            }
        }
        
        // Summary
        $this->info("📊 SUMMARY:");
        $this->line("-------------");
        $this->line("Total Roles: {$roles->count()}");
        $this->line("Total Permissions: {$permissions->count()}");
        
        $this->newLine();
        $this->info("✅ Use these commands to manage users:");
        $this->line("  • php artisan user:check-permissions {mobile} - Check user permissions");
        $this->line("  • php artisan user:assign-role {mobile} {role} - Assign role to user");
        $this->line("  • php artisan user:assign-permission {mobile} {permission} - Assign permission to user");
        $this->line("  • php artisan user:activate {mobile} - Activate user account");
        
        return 0;
    }
}
