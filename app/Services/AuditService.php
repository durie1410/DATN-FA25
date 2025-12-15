<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    /**
     * Log an action
     */
    public static function log(
        string $action,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
        ?string $description = null,
        ?Request $request = null,
        ?int $userId = null
    ): AuditLog {
        $request = $request ?: request();
        
        return AuditLog::create([
            'user_id' => $userId ?: auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->getKey() : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'description' => $description,
        ]);
    }

    /**
     * Log model creation
     */
    public static function logCreated(Model $model, ?string $description = null, ?int $userId = null): AuditLog
    {
        return self::log('created', $model, [], $model->getAttributes(), $description, null, $userId);
    }

    /**
     * Log model update
     */
    public static function logUpdated(Model $model, array $oldValues, ?string $description = null): AuditLog
    {
        return self::log('updated', $model, $oldValues, $model->getChanges(), $description);
    }

    /**
     * Log model deletion
     */
    public static function logDeleted(Model $model, ?string $description = null): AuditLog
    {
        return self::log('deleted', $model, $model->getAttributes(), [], $description);
    }

    /**
     * Log user login
     */
    public static function logLogin(?string $description = null): AuditLog
    {
        return self::log('login', null, [], [], $description ?: 'User logged in');
    }

    /**
     * Log user logout
     */
    public static function logLogout(?string $description = null): AuditLog
    {
        return self::log('logout', null, [], [], $description ?: 'User logged out');
    }

    /**
     * Log book borrow
     */
    public static function logBorrow(Model $borrow, ?string $description = null): AuditLog
    {
        return self::log('borrow', $borrow, [], $borrow->getAttributes(), $description ?: 'Book borrowed');
    }

    /**
     * Log book return
     */
    public static function logReturn(Model $borrow, ?string $description = null): AuditLog
    {
        return self::log('return', $borrow, [], $borrow->getAttributes(), $description ?: 'Book returned');
    }

    /**
     * Log fine creation
     */
    public static function logFine(Model $fine, ?string $description = null): AuditLog
    {
        return self::log('fine_created', $fine, [], $fine->getAttributes(), $description ?: 'Fine created');
    }

    /**
     * Log reservation
     */
    public static function logReservation(Model $reservation, ?string $description = null): AuditLog
    {
        return self::log('reservation', $reservation, [], $reservation->getAttributes(), $description ?: 'Book reserved');
    }

    /**
     * Get audit logs with filters
     */
    public static function getLogs(array $filters = [])
    {
        $query = AuditLog::with('user');

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['model_type'])) {
            $query->where('model_type', $filters['model_type']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get audit statistics
     */
    public static function getStatistics($days = 30)
    {
        $startDate = now()->subDays($days);

        return [
            'total_logs' => AuditLog::where('created_at', '>=', $startDate)->count(),
            'by_action' => AuditLog::where('created_at', '>=', $startDate)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->get(),
            'by_user' => AuditLog::where('created_at', '>=', $startDate)
                ->with('user')
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'daily_logs' => AuditLog::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];
    }
}
