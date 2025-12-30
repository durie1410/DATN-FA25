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
    'img',
    'type',
    'description',
    'status',
    'due_date',
    'notes',
    'created_by',
];


    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'damage_images' => 'array',
        'inspected_at' => 'datetime',
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

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    // Helper methods cho damage management
    public function hasDamageImages()
    {
        return !empty($this->damage_images) && is_array($this->damage_images) && count($this->damage_images) > 0;
    }

    public function getDamageImagesAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    public function getDamageSeverityTextAttribute()
    {
        return match($this->damage_severity) {
            'nhe' => 'Nhẹ',
            'trung_binh' => 'Trung bình',
            'nang' => 'Nặng',
            'mat_sach' => 'Mất sách',
            default => 'Chưa xác định',
        };
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
