<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'subject',
        'content',
        'variables',
        'channel',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function logs()
    {
        return $this->hasMany(NotificationLog::class, 'type', 'type');
    }

    // Scope để lấy template hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope để lấy template theo loại
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope để lấy template theo độ ưu tiên
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
