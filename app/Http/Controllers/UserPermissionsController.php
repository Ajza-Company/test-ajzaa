<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserPermissionsController extends Controller
{
    /**
     * Check current user permissions
     */
    public function checkMyPermissions(): JsonResponse
    {
        $user = Auth::guard('api')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $loginStatus = checkUserLoginStatus($user);
        $permissions = getUserPermissions($user);
        $roles = getUserRoles($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user_info' => $loginStatus['user_info'],
                'login_status' => $loginStatus,
                'roles' => $roles,
                'permissions' => $permissions,
                'can_access_dashboard' => checkUserAccess($user),
                'can_make_orders' => checkUserAccess($user, 'view-orders'),
                'can_manage_orders' => checkUserAccess($user, 'accept-orders'),
                'can_manage_offers' => checkUserAccess($user, 'control-offer'),
                'can_manage_products' => checkUserAccess($user, 'edit-products'),
                'can_manage_stores' => checkUserAccess($user, 'edit-stores'),
                'can_manage_users' => checkUserAccess($user, 'edit-users'),
                'can_manage_categories' => checkUserAccess($user, 'edit-categories'),
                'can_view_statistics' => checkUserAccess($user, 'view-orders-statistics'),
            ]
        ]);
    }

    /**
     * Check specific user permissions by mobile
     */
    public function checkUserPermissionsByMobile(Request $request): JsonResponse
    {
        $request->validate([
            'mobile' => 'required|string'
        ]);

        $user = User::where('full_mobile', $request->mobile)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $loginStatus = checkUserLoginStatus($user);
        $permissions = getUserPermissions($user);
        $roles = getUserRoles($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user_info' => $loginStatus['user_info'],
                'login_status' => $loginStatus,
                'roles' => $roles,
                'permissions' => $permissions,
                'can_access_dashboard' => checkUserAccess($user),
                'can_make_orders' => checkUserAccess($user, 'view-orders'),
                'can_manage_orders' => checkUserAccess($user, 'accept-orders'),
                'can_manage_offers' => checkUserAccess($user, 'control-offer'),
                'can_manage_products' => checkUserAccess($user, 'edit-products'),
                'can_manage_stores' => checkUserAccess($user, 'edit-stores'),
                'can_manage_users' => checkUserAccess($user, 'edit-users'),
                'can_manage_categories' => checkUserAccess($user, 'edit-categories'),
                'can_view_statistics' => checkUserAccess($user, 'view-orders-statistics'),
            ]
        ]);
    }

    /**
     * Check if user can access specific permission
     */
    public function checkPermission(Request $request): JsonResponse
    {
        $request->validate([
            'permission' => 'required|string'
        ]);

        $user = Auth::guard('api')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $permission = $request->permission;
        $hasAccess = checkUserAccess($user, $permission);

        return response()->json([
            'success' => true,
            'data' => [
                'permission' => $permission,
                'has_access' => $hasAccess,
                'user_id' => $user->id,
                'user_roles' => getUserRoles($user),
                'user_permissions' => getUserPermissions($user)
            ]
        ]);
    }

    /**
     * Check if user has specific role
     */
    public function checkRole(Request $request): JsonResponse
    {
        $request->validate([
            'role' => 'required|string'
        ]);

        $user = Auth::guard('api')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $role = $request->role;
        $hasRole = checkUserRole($user, $role);

        return response()->json([
            'success' => true,
            'data' => [
                'role' => $role,
                'has_role' => $hasRole,
                'user_id' => $user->id,
                'user_roles' => getUserRoles($user)
            ]
        ]);
    }

    /**
     * Get all available permissions
     */
    public function getAllPermissions(): JsonResponse
    {
        $permissions = \Spatie\Permission\Models\Permission::orderBy('group_name')
            ->orderBy('name')
            ->get()
            ->groupBy('group_name');

        return response()->json([
            'success' => true,
            'data' => [
                'permissions' => $permissions,
                'total_count' => \Spatie\Permission\Models\Permission::count()
            ]
        ]);
    }

    /**
     * Get all available roles
     */
    public function getAllRoles(): JsonResponse
    {
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'roles' => $roles,
                'total_count' => $roles->count()
            ]
        ]);
    }
}
