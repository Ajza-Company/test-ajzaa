<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateTestToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:test-token {email?} {--role=supplier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a test token for API testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';
        $role = $this->option('role');

        // Check if user exists
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => 'Test User',
                'email' => $email,
                'password' => Hash::make('password123'),
                'is_active' => true,
                'is_registered' => true
            ]);

            $this->info("Created new user: {$user->email}");
        } else {
            $this->info("Using existing user: {$user->email}");
        }

        // Assign role if specified
        if ($role && !$user->hasRole($role)) {
            $user->assignRole($role);
            $this->info("Assigned role: {$role}");
        }

        // Generate token
        $token = $user->createToken('test-token')->plainTextToken;

        $this->info('✅ Token generated successfully!');
        $this->info('🔑 Token: ' . $token);
        $this->info('📧 Email: ' . $user->email);
        $this->info('🔒 Password: password123');
        $this->info('');
        $this->info('📱 Use this token in your API requests:');
        $this->info('Authorization: Bearer ' . $token);
        $this->info('');
        $this->info('🧪 Test with:');
        $this->info('GET /api/supplier/rep-orders/all');
        $this->info('GET /api/frontend/rep-orders');

        return Command::SUCCESS;
    }
}
