<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AutoAssignAllUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:auto-assign-all {--force : Force reassignment even if user has permissions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto assign permissions to all users based on their roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $this->info("🔐 AUTO ASSIGNING PERMISSIONS TO ALL USERS");
        $this->line("=============================================");
        
        $users = User::with('roles')->get();
        $totalUsers = $users->count();
        $processedUsers = 0;
        $skippedUsers = 0;
        $errorUsers = 0;
        
        $this->newLine();
        $this->info("Found {$totalUsers} users in the system");
        $this->newLine();
        
        $progressBar = $this->output->createProgressBar($totalUsers);
        $progressBar->start();
        
        foreach ($users as $user) {
            try {
                $result = $this->processUser($user, $force);
                
                if ($result === 'processed') {
                    $processedUsers++;
                } elseif ($result === 'skipped') {
                    $skippedUsers++;
                } else {
                    $errorUsers++;
                }
                
            } catch (\Exception $e) {
                $this->error("Error processing user {$user->id}: " . $e->getMessage());
                $errorUsers++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info("📊 SUMMARY:");
        $this->line("-------------");
        $this->line("Total Users: {$totalUsers}");
        $this->line("✅ Processed: {$processedUsers}");
        $this->line("⏭️  Skipped: {$skippedUsers}");
        $this->line("❌ Errors: {$errorUsers}");
        
        $this->newLine();
        $this->info("🎯 Next Steps:");
        $this->line("1. Test login for users");
        $this->line("2. Check if permissions are working correctly");
        $this->line("3. Remove this command when no longer needed");
        
        return 0;
    }
    
    /**
     * Process individual user
     */
    private function processUser($user, $force): string
    {
        // Skip if user has no roles
        if (!$user->roles || $user->roles->isEmpty()) {
            return 'skipped';
        }
        
        // Skip if user already has permissions and force is false
        if (!$force && $user->permissions && $user->permissions->count() > 0) {
            return 'skipped';
        }
        
        // Get user's first role
        $userRole = $user->roles->first();
        
        // Skip if user is Client
        if ($userRole->name === 'Client') {
            return 'skipped';
        }
        
        // Assign permissions based on role
        $this->assignRolePermissions($user, $userRole);
        
        return 'processed';
    }
    
    /**
     * Assign permissions based on role
     */
    private function assignRolePermissions($user, $role): void
    {
        $permissions = [];

        switch ($role->name) {
            case 'Admin':
                $permissions = $this->getAdminPermissions();
                break;
            case 'Supplier':
                $permissions = $this->getSupplierPermissions();
                break;
            case 'Workshop':
                $permissions = $this->getWorkshopPermissions();
                break;
            case 'Representative':
                $permissions = $this->getRepresentativePermissions();
                break;
        }

        // Assign permissions to user
        if (!empty($permissions)) {
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission && !$user->hasPermissionTo($permissionName)) {
                    $user->givePermissionTo($permission);
                }
            }
        }
    }
    
    /**
     * Get Admin permissions
     */
    private function getAdminPermissions(): array
    {
        return [
            'a.show-all-users',
            'a.control-user',
            'a.show-all-stores',
            'a.control-store',
            'a.show-all-repSales',
            'a.control-repSales',
            'a.show-all-promos',
            'a.control-promo',
            'a.show-all-products',
            'a.control-product',
            'a.show-all-states',
            'a.control-state',
            'a.show-all-offers',
            'a.control-offers',
            'a.show-all-chat',
            'a.control-chat',
            // Also give supplier permissions
            'show-all-permissions',
            'view-orders',
            'accept-orders',
            'view-offers',
            'control-offer',
            'view-users',
            'edit-users',
            'view-stores',
            'edit-stores',
            'view-categories',
            'edit-categories',
            'view-products',
            'edit-products',
            'view-orders-statistics'
        ];
    }

    /**
     * Get Supplier permissions
     */
    private function getSupplierPermissions(): array
    {
        return [
            'show-all-permissions',
            'view-orders',
            'accept-orders',
            'view-offers',
            'control-offer',
            'view-users',
            'edit-users',
            'view-stores',
            'edit-stores',
            'view-categories',
            'edit-categories',
            'view-products',
            'edit-products',
            'view-orders-statistics'
        ];
    }

    /**
     * Get Workshop permissions
     */
    private function getWorkshopPermissions(): array
    {
        return [
            'view-orders',
            'accept-orders',
            'view-offers',
            'view-stores',
            'view-products',
            'view-categories'
        ];
    }

    /**
     * Get Representative permissions
     */
    private function getRepresentativePermissions(): array
    {
        return [
            'view-orders',
            'accept-orders',
            'view-offers',
            'view-stores',
            'view-products',
            'view-categories'
        ];
    }
}
