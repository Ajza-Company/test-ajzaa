<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class MakeUserClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-client {user_id : The ID of the user to make client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change a user role to CLIENT for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        try {
            // Find user
            $user = User::find($userId);
            
            if (!$user) {
                $this->error("User with ID {$userId} not found!");
                return 1;
            }
            
            $this->info("Found user: {$user->name} ({$user->email})");
            
            // Get current roles
            $currentRoles = $user->getRoleNames();
            $this->info("Current roles: " . implode(', ', $currentRoles->toArray()));
            
            // Find Client role
            $clientRole = Role::where('name', RoleEnum::CLIENT)->first();
            
            if (!$clientRole) {
                $this->warn("Client role not found! Creating it...");
                $clientRole = Role::create([
                    'name' => RoleEnum::CLIENT,
                    'guard_name' => 'api'
                ]);
                $this->info("Client role created with ID: {$clientRole->id}");
            }
            
            // Remove all existing roles and assign Client role
            $user->syncRoles([$clientRole]);
            
            $this->info("Successfully updated user roles!");
            
            // Verify the change
            $newRoles = $user->fresh()->getRoleNames();
            $this->info("New roles: " . implode(', ', $newRoles->toArray()));
            
            $this->info("User {$user->name} is now a CLIENT and can be used for testing!");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
