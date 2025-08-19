<?php

/**
 * Check if user can access specific feature
 * 
 * @param \App\Models\User $user
 * @param string $permission
 * @return bool
 */
function checkUserAccess($user, $permission = null)
{
    // Check if user exists and is authenticated
    if (!$user) {
        return false;
    }

    // Check basic user status
    if (!$user->is_active || !$user->is_registered || $user->deletion_status !== 'active') {
        return false;
    }

    // Check if user has any role
    if (!$user->roles || $user->roles->isEmpty()) {
        return false;
    }

    // If no specific permission required, just check if user has any role
    if (!$permission) {
        return true;
    }

    // Check if user has the specific permission
    foreach ($user->roles as $role) {
        if ($role->permissions) {
            foreach ($role->permissions as $rolePermission) {
                if ($rolePermission->name === $permission) {
                    return true;
                }
            }
        }
    }

    // Check direct user permissions
    if ($user->permissions) {
        foreach ($user->permissions as $userPermission) {
            if ($userPermission->name === $permission) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Check if user has specific role
 * 
 * @param \App\Models\User $user
 * @param string $role
 * @return bool
 */
function checkUserRole($user, $role)
{
    if (!$user || !$user->roles) {
        return false;
    }

    foreach ($user->roles as $userRole) {
        if ($userRole->name === $role) {
            return true;
        }
    }

    return false;
}

/**
 * Get user permissions list
 * 
 * @param \App\Models\User $user
 * @return array
 */
function getUserPermissions($user)
{
    if (!$user) {
        return [];
    }

    $permissions = [];

    // Get permissions from roles
    if ($user->roles) {
        foreach ($user->roles as $role) {
            if ($role->permissions) {
                foreach ($role->permissions as $permission) {
                    $permissions[] = [
                        'name' => $permission->name,
                        'group_name' => $permission->group_name,
                        'friendly_name' => $permission->friendly_name,
                        'source' => 'role',
                        'role_name' => $role->name
                    ];
                }
            }
        }
    }

    // Get direct user permissions
    if ($user->permissions) {
        foreach ($user->permissions as $permission) {
            $permissions[] = [
                'name' => $permission->name,
                'group_name' => $permission->group_name,
                'friendly_name' => $permission->friendly_name,
                'source' => 'direct',
                'role_name' => null
            ];
        }
    }

    // Remove duplicates
    $uniquePermissions = [];
    foreach ($permissions as $permission) {
        $key = $permission['name'];
        if (!isset($uniquePermissions[$key])) {
            $uniquePermissions[$key] = $permission;
        }
    }

    return array_values($uniquePermissions);
}

/**
 * Get user roles list
 * 
 * @param \App\Models\User $user
 * @return array
 */
function getUserRoles($user)
{
    if (!$user || !$user->roles) {
        return [];
    }

    $roles = [];
    foreach ($user->roles as $role) {
        $roles[] = [
            'id' => $role->id,
            'name' => $role->name,
            'guard_name' => $role->guard_name,
            'created_at' => $role->created_at
        ];
    }

    return $roles;
}

/**
 * Check if user can login
 * 
 * @param \App\Models\User $user
 * @return array
 */
function checkUserLoginStatus($user)
{
    if (!$user) {
        return [
            'can_login' => false,
            'reasons' => ['User not found'],
            'status' => 'error'
        ];
    }

    $reasons = [];
    $canLogin = true;

    // Check is_active
    if (!$user->is_active) {
        $reasons[] = 'Account is not active';
        $canLogin = false;
    }

    // Check is_registered
    if (!$user->is_registered) {
        $reasons[] = 'Account is not registered';
        $canLogin = false;
    }

    // Check deletion_status
    if ($user->deletion_status !== 'active') {
        $reasons[] = "Account deletion status: {$user->deletion_status}";
        $canLogin = false;
    }

    // Check if user has any role
    if (!$user->roles || $user->roles->isEmpty()) {
        $reasons[] = 'User has no assigned role';
        $canLogin = false;
    }

    return [
        'can_login' => $canLogin,
        'reasons' => $reasons,
        'status' => $canLogin ? 'success' : 'error',
        'user_info' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->full_mobile,
            'is_active' => $user->is_active,
            'is_registered' => $user->is_registered,
            'deletion_status' => $user->deletion_status
        ]
    ];
}
