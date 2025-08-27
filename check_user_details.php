<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== USER DETAILS CHECK ===\n\n";

// Check specific users
$users = [2, 8, 1, 5]; // Admin, Supplier, Regular users

foreach ($users as $userId) {
    try {
        $user = DB::select("
            SELECT id, name, email, full_mobile, is_active, password 
            FROM users 
            WHERE id = ? AND deleted_at IS NULL
        ", [$userId]);
        
        if (count($user) > 0) {
            $user = $user[0];
            echo "User ID: {$user->id}\n";
            echo "Name: {$user->name}\n";
            echo "Email: {$user->email}\n";
            echo "Phone: {$user->full_mobile}\n";
            echo "Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
            echo "Password Hash: " . substr($user->password, 0, 20) . "...\n";
            
            // Test common passwords
            $testPasswords = ['password', '123456', 'admin', 'password123'];
            foreach ($testPasswords as $testPass) {
                if (Hash::check($testPass, $user->password)) {
                    echo "✅ Password found: {$testPass}\n";
                    break;
                }
            }
            echo "---\n";
        }
    } catch (Exception $e) {
        echo "Error checking user {$userId}: " . $e->getMessage() . "\n";
    }
}

echo "\n=== END ===\n";

