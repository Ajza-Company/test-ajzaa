<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class MakeUserClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Find user with ID 2
            $user = User::find(2);
            
            if (!$user) {
                $this->command->error("User with ID 2 not found!");
                return;
            }
            
            $this->command->info("Found user: {$user->name} ({$user->email})");
            
            // Get current roles
            $currentRoles = $user->getRoleNames();
            $this->command->info("Current roles: " . implode(', ', $currentRoles->toArray()));
            
            // Find Client role
            $clientRole = Role::where('name', RoleEnum::CLIENT)->first();
            
            if (!$clientRole) {
                $this->command->warn("Client role not found! Creating it...");
                $clientRole = Role::create([
                    'name' => RoleEnum::CLIENT,
                    'guard_name' => 'api'
                ]);
                $this->command->info("Client role created with ID: {$clientRole->id}");
            }
            
            // Remove all existing roles and assign Client role
            $user->syncRoles([$clientRole]);
            
            $this->command->info("Successfully updated user roles!");
            
            // Verify the change
            $newRoles = $user->fresh()->getRoleNames();
            $this->command->info("New roles: " . implode(', ', $newRoles->toArray()));
            
            $this->command->info("User {$user->name} is now a CLIENT and can be used for testing!");
            
        } catch (\Exception $e) {
            $this->command->error("Error: " . $e->getMessage());
        }
    }
}
