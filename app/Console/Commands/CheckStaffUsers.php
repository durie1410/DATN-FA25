<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckStaffUsers extends Command
{
    protected $signature = 'check:staff-users';
    protected $description = 'Check staff users in database';

    public function handle()
    {
        $staffUsers = User::where('role', 'staff')->get(['id', 'name', 'email', 'role']);
        
        $this->info('Staff users in database:');
        foreach ($staffUsers as $user) {
            $this->line("ID: {$user->id} - Name: {$user->name} - Email: {$user->email} - Role: {$user->role}");
        }
        
        if ($staffUsers->isEmpty()) {
            $this->warn('No staff users found!');
        }
        
        return 0;
    }
}
















