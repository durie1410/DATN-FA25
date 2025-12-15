<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'receipt_date',
        'book_id',
        'quantity',
        'storage_location',
        'storage_type',
        'unit_price',
        'total_price',
        'supplier',
        'received_by',
        'approved_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'receipt_id');
    }

    // Scope để lấy phiếu đang chờ phê duyệt
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope để lấy phiếu đã phê duyệt
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Tạo số phiếu tự động
    public static function generateReceiptNumber()
    {
        $prefix = 'PNK';
        $date = now()->format('Ymd');
        $lastReceipt = self::where('receipt_number', 'like', $prefix . $date . '%')
            ->orderBy('receipt_number', 'desc')
            ->first();
        
        if ($lastReceipt) {
            $lastNumber = intval(substr($lastReceipt->receipt_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $date . $newNumber;
    }
}
