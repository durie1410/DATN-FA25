<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'barcode',
        'location',
        'condition',
        'status',
        'purchase_price',
        'purchase_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'book_id', 'book_id');
    }

    // Scope để lấy sách có sẵn
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Co san');
    }

    // Scope để lấy sách đang mượn
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'Dang muon');
    }

    // Scope để lấy sách theo tình trạng
    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    // Scope để lấy sách theo vị trí
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    // Kiểm tra sách có sẵn để mượn không
    public function isAvailable()
    {
        return $this->status === 'Co san';
    }

    // Kiểm tra sách có đang mượn không
    public function isBorrowed()
    {
        return $this->status === 'Dang muon';
    }

    // Lấy số ngày từ khi mua
    public function getDaysSincePurchaseAttribute()
    {
        if ($this->purchase_date) {
            return now()->diffInDays($this->purchase_date);
        }
        return null;
    }
}
