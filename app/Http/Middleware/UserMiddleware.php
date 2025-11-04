<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Chỉ cho phép user thường truy cập
        if ($user->role === 'user') {
            return $next($request);
        }

        // Redirect admin và staff về dashboard tương ứng
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }

        abort(403, 'Bạn không có quyền truy cập trang này.');
    }
}

