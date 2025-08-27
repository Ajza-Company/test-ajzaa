<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== AVAILABLE PHONE NUMBERS FOR LOGIN ===\n\n";

try {
    $users = DB::select("
        SELECT id, name, email, full_mobile, is_active 
        FROM users 
        WHERE deleted_at IS NULL 
        AND full_mobile IS NOT NULL 
        AND full_mobile != '' 
        ORDER BY id 
        LIMIT 15
    ");
    
    if (count($users) > 0) {
        foreach ($users as $user) {
            echo "ID: {$user->id} | Name: {$user->name} | Phone: {$user->full_mobile} | Email: {$user->email} | Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
        }
    } else {
        echo "No users with phone numbers found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== END ===\n";
