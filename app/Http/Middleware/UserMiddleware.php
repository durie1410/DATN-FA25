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
    /**
     * Handle an incoming request.
     * Chỉ cho phép user thường truy cập, redirect admin/staff về dashboard của họ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = auth()->user();
        
        // Chỉ cho phép user thường truy cập
        if ($user->isUser()) {
            return $next($request);
        }

        // Redirect admin về dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Bạn đã được chuyển hướng đến trang quản trị.');
        }

        // Redirect staff (librarian/warehouse) về admin dashboard
        if ($user->isStaff()) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Bạn đã được chuyển hướng đến trang quản trị.');
        }

        abort(403, 'Bạn không có quyền truy cập trang này.');
    }
}

