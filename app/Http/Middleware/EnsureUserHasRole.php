<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
	public function handle(Request $request, Closure $next, ...$roles)
	{
		$user = $request->user();
		if (!$user) {
			return response()->json(['message' => 'Unauthenticated'], 401);
		}

		// Prefer a 'role' attribute on users; fallback to spatie/permission if available
		$userRole = $user->role ?? null;
		$ok = false;
		if ($userRole && in_array($userRole, $roles, true)) {
			$ok = true;
		}
		elseif (method_exists($user, 'hasAnyRole')) {
			$ok = $user->hasAnyRole($roles);
		}

		if (!$ok) {
			return response()->json(['message' => 'Forbidden'], 403);
		}

		return $next($request);
	}
}


