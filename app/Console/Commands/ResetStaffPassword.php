<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetStaffPassword extends Command
{
    protected $signature = 'reset:staff-password {email} {password}';
    protected $description = 'Reset staff user password';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found!");
            return 1;
        }
        
        $user->password = Hash::make($password);
        $user->save();
        
        $this->info("Password reset successfully for: {$user->name}");
        $this->info("New password: {$password}");
        
        return 0;
    }
}
















