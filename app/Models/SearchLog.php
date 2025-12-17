<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'type',
        'filters',
        'results_count',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'filters' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope để lấy log theo loại tìm kiếm
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope để lấy log theo user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope để lấy log theo khoảng thời gian
    public function scopeByDateRange($query, $fromDate, $toDate)
    {
        return $query->whereBetween('created_at', [$fromDate, $toDate]);
    }

    // Scope để lấy từ khóa tìm kiếm phổ biến
    public function scopePopularQueries($query, $limit = 10)
    {
        return $query->selectRaw('query, COUNT(*) as search_count')
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit($limit);
    }
}
