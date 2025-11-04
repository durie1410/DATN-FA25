<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\AuditService;
use App\Notifications\WelcomeNotification;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // SECURITY FIX: Rate limiting cho login attempts
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Throttle login attempts
        $key = 'login.attempts.' . $request->ip();
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 5) {
            return back()->withErrors([
                'email' => 'Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau 15 phút.',
            ])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Reset attempts counter on successful login
            cache()->forget($key);
            $request->session()->regenerate();
            
            // Log successful login
            AuditService::logLogin('User logged in successfully');
            
            // Redirect based on user role
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'staff') {
                return redirect()->route('staff.dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        // Increment failed attempts
        cache()->put($key, $attempts + 1, now()->addMinutes(15));

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user', // SECURITY FIX: Always 'user'
            ]);

            // Assign role using Spatie Permission
            $user->assignRole('user');

            // Log user registration AFTER user is created and committed
            AuditService::logCreated($user, 'New user registered', $user->id);

            DB::commit();

            // Send welcome email notification
            try {
                $user->notify(new WelcomeNotification($user));
            } catch (\Exception $e) {
                // Log email error but don't fail registration
                Log::error('Failed to send welcome email: ' . $e->getMessage());
            }

            Auth::login($user);

            return redirect()->route('home')->with('success', 'Đăng ký thành công! Email chào mừng đã được gửi đến hộp thư của bạn.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'email' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.',
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Log logout before destroying session
        AuditService::logLogout('User logged out');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
}
