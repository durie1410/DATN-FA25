<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'type',
        'quantity',
        'from_location',
        'to_location',
        'condition_before',
        'condition_after',
        'status_before',
        'status_after',
        'reason',
        'notes',
        'performed_by',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // Scope để lấy giao dịch theo loại
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope để lấy giao dịch theo khoảng thời gian
    public function scopeByDateRange($query, $fromDate, $toDate)
    {
        return $query->whereBetween('created_at', [$fromDate, $toDate]);
    }

    // Scope để lấy giao dịch theo người thực hiện
    public function scopeByPerformer($query, $userId)
    {
        return $query->where('performed_by', $userId);
    }
}
