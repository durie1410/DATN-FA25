<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Kiểm tra permission của user
     * Admin tự động có tất cả permissions
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = auth()->user();
        
        // Admin có tất cả quyền
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Kiểm tra permission
        if (!$user->can($permission)) {
            abort(403, "Bạn không có quyền thực hiện hành động này. (Yêu cầu: {$permission})");
        }

        return $next($request);
    }
}
