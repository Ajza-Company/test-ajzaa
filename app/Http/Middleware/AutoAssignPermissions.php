<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AutoAssignPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $this->autoAssignPermissions($user);
        }

        return $next($request);
    }

    /**
     * Auto assign permissions based on user role
     */
    private function autoAssignPermissions($user): void
    {
        // Skip if user already has permissions
        if ($user->permissions && $user->permissions->count() > 0) {
            return;
        }

        // Skip if user has no roles
        if (!$user->roles || $user->roles->isEmpty()) {
            return;
        }

        // Get user's first role
        $userRole = $user->roles->first();
        
        // Skip if user is Client
        if ($userRole->name === 'Client') {
            return;
        }

        // Auto assign permissions based on role
        $this->assignRolePermissions($user, $userRole);
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
