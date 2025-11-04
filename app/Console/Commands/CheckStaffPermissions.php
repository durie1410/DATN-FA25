<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CheckStaffPermissions extends Command
{
    protected $signature = 'check:staff-permissions';
    protected $description = 'Check staff user permissions';

    public function handle()
    {
        $staffUsers = User::where('role', 'staff')->get();
        
        foreach ($staffUsers as $user) {
            $this->info("User: {$user->name} ({$user->email})");
            $this->line("Role: {$user->role}");
            
            // Check Spatie roles
            $roles = $user->getRoleNames();
            $this->line("Spatie Roles: " . $roles->implode(', '));
            
            // Check permissions
            $permissions = $user->getAllPermissions();
            $this->line("Permissions count: " . $permissions->count());
            
            // Check specific permissions
            $hasViewBooks = $user->hasPermissionTo('view-books');
            $hasViewDashboard = $user->hasPermissionTo('view-dashboard');
            
            $this->line("Has view-books: " . ($hasViewBooks ? 'YES' : 'NO'));
            $this->line("Has view-dashboard: " . ($hasViewDashboard ? 'YES' : 'NO'));
            
            $this->line("---");
        }
        
        // Check role permissions
        $staffRole = Role::where('name', 'staff')->first();
        if ($staffRole) {
            $this->info("Staff role permissions:");
            $permissions = $staffRole->permissions;
            foreach ($permissions as $permission) {
                $this->line("- {$permission->name}");
            }
        }
        
        return 0;
    }
}
















