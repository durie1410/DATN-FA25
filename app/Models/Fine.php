<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Fine extends Model
{
    use HasFactory,SoftDeletes; 
    protected $fillable = [
        'borrow_id',
        'reader_id',
 'borrow_item_id',        
 'amount',
        'type',
        'description',
        'status',
        'due_date',
        'paid_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];
public function borrowItem()
    {
        return $this->belongsTo(BorrowItem::class);
    }
    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }

    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope để lấy phạt chưa thanh toán
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope để lấy phạt đã thanh toán
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    // Scope để lấy phạt quá hạn
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::today())
            ->where('status', 'pending');
    }

    // Scope để lấy phạt theo loại
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Kiểm tra phạt có quá hạn không
    public function isOverdue()
    {
        return $this->due_date < Carbon::today() && $this->status === 'pending';
    }

    // Tính số ngày quá hạn
    public function getDaysOverdueAttribute()
    {
        if ($this->isOverdue()) {
            return Carbon::today()->diffInDays($this->due_date);
        }
        return 0;
    }
}
