<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Services\AuditService;
use App\Notifications\WelcomeNotification;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            DB::beginTransaction();
            
            // Check if user already exists
            $user = User::where('google_id', $googleUser->getId())
                      ->orWhere('email', $googleUser->getEmail())
                      ->first();

            if ($user) {
                // Update existing user with Google info if needed
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                    ]);
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()), // Random password for Google users
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);

                // Assign role using Spatie Permission
                $user->assignRole('user');

                // Log user registration
                AuditService::logCreated($user, 'New user registered via Google', $user->id);

                // Send welcome email notification
                try {
                    $user->notify(new WelcomeNotification($user));
                } catch (\Exception $e) {
                    // Log email error but don't fail registration
                    Log::error('Failed to send welcome email: ' . $e->getMessage());
                }
            }

            DB::commit();

            // Login user
            Auth::login($user);

            // Log successful login
            AuditService::logLogin('User logged in via Google');

            // Redirect based on user role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
            } elseif ($user->role === 'staff') {
                return redirect()->route('staff.dashboard')->with('success', 'Đăng nhập thành công!');
            } else {
                return redirect()->route('home')->with('success', 'Đăng ký/Đăng nhập thành công!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Google OAuth error: ' . $e->getMessage());
            
            return redirect()->route('login')->withErrors([
                'email' => 'Có lỗi xảy ra khi đăng nhập với Google. Vui lòng thử lại.',
            ]);
        }
    }
}