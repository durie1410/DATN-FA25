<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected
     */
    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    /**
     * Scope to filter by action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user
     */
    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by model type
     */
    public function scopeModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted description
     */
    public function getFormattedDescriptionAttribute()
    {
        $userName = $this->user ? $this->user->name : 'Unknown User';
        $modelName = class_basename($this->model_type);
        
        switch ($this->action) {
            case 'created':
                return "{$userName} đã tạo {$modelName} mới";
            case 'updated':
                return "{$userName} đã cập nhật {$modelName}";
            case 'deleted':
                return "{$userName} đã xóa {$modelName}";
            case 'login':
                return "{$userName} đã đăng nhập";
            case 'logout':
                return "{$userName} đã đăng xuất";
            case 'borrow':
                return "{$userName} đã mượn sách";
            case 'return':
                return "{$userName} đã trả sách";
            default:
                return $this->description ?: "{$userName} đã thực hiện hành động {$this->action}";
        }
    }

    /**
     * Get changes summary
     */
    public function getChangesSummaryAttribute()
    {
        if (!$this->old_values || !$this->new_values) {
            return null;
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[] = [
                    'field' => $key,
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }

        return $changes;
    }
}























