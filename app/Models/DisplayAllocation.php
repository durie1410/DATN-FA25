<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisplayAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'inventory_id',
        'quantity_on_display',
        'quantity_in_stock',
        'display_area',
        'display_start_date',
        'display_end_date',
        'allocated_by',
        'notes',
    ];

    protected $casts = [
        'quantity_on_display' => 'integer',
        'quantity_in_stock' => 'integer',
        'display_start_date' => 'date',
        'display_end_date' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function allocator()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    // Scope để lấy phân bổ đang hoạt động
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('display_end_date')
              ->orWhere('display_end_date', '>=', now()->toDateString());
        });
    }

    // Kiểm tra phân bổ có đang hoạt động không
    public function isActive()
    {
        return $this->display_end_date === null || $this->display_end_date >= now()->toDateString();
    }
}
