<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestStaffLogin extends Command
{
    protected $signature = 'test:staff-login {email} {password}';
    protected $description = 'Test staff login functionality';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        $this->info("Testing login for: {$email}");
        
        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found!");
            return 1;
        }
        
        $this->info("User found: {$user->name} (Role: {$user->role})");
        
        // Check password
        if (!password_verify($password, $user->password)) {
            $this->error("Invalid password!");
            return 1;
        }
        
        $this->info("Password is correct!");
        
        // Check permissions
        $hasViewBooks = $user->hasPermissionTo('view-books');
        $hasViewDashboard = $user->hasPermissionTo('view-dashboard');
        
        $this->info("Has view-books permission: " . ($hasViewBooks ? 'YES' : 'NO'));
        $this->info("Has view-dashboard permission: " . ($hasViewDashboard ? 'YES' : 'NO'));
        
        // Check if user can access staff routes
        if ($user->role === 'staff' || $user->role === 'admin') {
            $this->info("✅ User can access staff dashboard!");
        } else {
            $this->error("❌ User cannot access staff dashboard!");
        }
        
        return 0;
    }
}
















