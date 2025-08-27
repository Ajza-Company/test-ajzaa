<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== PERMISSIONS AND ROLES CHECK ===\n\n";

// Check if permissions table exists
echo "1. CHECKING PERMISSIONS TABLE:\n";
try {
    $exists = DB::select("SHOW TABLES LIKE 'permissions'");
    if (count($exists) > 0) {
        echo "   permissions table exists\n";
        $permissions = DB::select("SELECT * FROM permissions LIMIT 10");
        foreach ($permissions as $perm) {
            echo "     ID: {$perm->id} | Name: {$perm->name}\n";
        }
    } else {
        echo "   permissions table does not exist\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Check if roles table exists
echo "2. CHECKING ROLES TABLE:\n";
try {
    $exists = DB::select("SHOW TABLES LIKE 'roles'");
    if (count($exists) > 0) {
        echo "   roles table exists\n";
        $roles = DB::select("SELECT * FROM roles LIMIT 10");
        foreach ($roles as $role) {
            echo "     ID: {$role->id} | Name: {$role->name}\n";
        }
    } else {
        echo "   roles table does not exist\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Check if role_user table exists
echo "3. CHECKING ROLE_USER TABLE:\n";
try {
    $exists = DB::select("SHOW TABLES LIKE 'role_user'");
    if (count($exists) > 0) {
        echo "   role_user table exists\n";
        $roleUsers = DB::select("SELECT * FROM role_user LIMIT 10");
        foreach ($roleUsers as $ru) {
            echo "     User ID: {$ru->user_id} | Role ID: {$ru->role_id}\n";
        }
    } else {
        echo "   role_user table does not exist\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Check if permission_role table exists
echo "4. CHECKING PERMISSION_ROLE TABLE:\n";
try {
    $exists = DB::select("SHOW TABLES LIKE 'permission_role'");
    if (count($exists) > 0) {
        echo "   permission_role table exists\n";
        $permRoles = DB::select("SELECT * FROM permission_role LIMIT 10");
        foreach ($permRoles as $pr) {
            echo "     Permission ID: {$pr->permission_id} | Role ID: {$pr->role_id}\n";
        }
    } else {
        echo "   permission_role table does not exist\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n=== END ===\n";
