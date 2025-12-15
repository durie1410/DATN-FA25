<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiAuditLogger
{
	public function handle(Request $request, Closure $next)
	{
		$response = $next($request);

		try {
			$actorId = optional($request->user())->id;
			$action = $request->method().' '.$request->path();
			$entityType = null;
			$entityId = null;
			$payload = $request->except(['password', 'password_confirmation', 'token']);
			$meta = [
				'ip' => $request->ip(),
				'status' => $response->getStatusCode(),
				'query' => $request->query(),
			];

			DB::table('audit_logs')->insert([
				'actor_id' => $actorId,
				'action' => $action,
				'entity_type' => $entityType,
				'entity_id' => $entityId,
				'metadata' => json_encode(['payload' => $payload, 'meta' => $meta]),
				'created_at' => now(),
				'updated_at' => now(),
			]);
		} catch (\Throwable $e) {
			// ignore auditing failures
		}

		return $response;
	}
}


