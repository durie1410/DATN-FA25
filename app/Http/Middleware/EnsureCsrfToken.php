<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EnsureCsrfToken
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
        // Skip CSRF check for API routes
        if ($request->is('api/*') || $request->expectsJson()) {
            return $next($request);
        }

        // Skip CSRF check for GET requests
        if ($request->isMethod('GET')) {
            return $next($request);
        }

        // Check if CSRF token exists
        if (!$request->has('_token') && !$request->header('X-CSRF-TOKEN')) {
            return redirect()->back()
                ->withErrors(['csrf' => 'CSRF token không tồn tại. Vui lòng thử lại.'])
                ->withInput();
        }

        // Verify CSRF token
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        if (!hash_equals(Session::token(), $token)) {
            return redirect()->back()
                ->withErrors(['csrf' => 'CSRF token không hợp lệ. Vui lòng thử lại.'])
                ->withInput();
        }

        return $next($request);
    }
}























