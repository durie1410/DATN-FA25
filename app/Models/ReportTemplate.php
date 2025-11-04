<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'columns',
        'filters',
        'group_by',
        'order_by',
        'is_active',
        'is_public',
        'created_by',
    ];

    protected $casts = [
        'columns' => 'array',
        'filters' => 'array',
        'group_by' => 'array',
        'order_by' => 'array',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope để lấy template hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope để lấy template công khai
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Scope để lấy template theo loại
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
