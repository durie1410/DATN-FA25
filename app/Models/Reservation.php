<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'reader_id',
        'user_id',
        'status',
        'priority',
        'reservation_date',
        'expiry_date',
        'ready_date',
        'pickup_date',
        'notes',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'expiry_date' => 'date',
        'ready_date' => 'date',
        'pickup_date' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope để lấy đặt trước đang chờ
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope để lấy đặt trước đã xác nhận
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // Scope để lấy đặt trước sẵn sàng
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    // Scope để lấy đặt trước đã hết hạn
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', Carbon::today())
            ->where('status', 'pending');
    }

    // Scope để sắp xếp theo độ ưu tiên
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc')->orderBy('reservation_date', 'asc');
    }

    // Kiểm tra đặt trước có hết hạn không
    public function isExpired()
    {
        return $this->expiry_date < Carbon::today() && $this->status === 'pending';
    }

    // Kiểm tra đặt trước có sẵn sàng không
    public function isReady()
    {
        return $this->status === 'ready';
    }

    // Tính số ngày chờ
    public function getDaysWaitingAttribute()
    {
        return Carbon::today()->diffInDays($this->reservation_date);
    }

    // Tính số ngày còn lại để hết hạn
    public function getDaysUntilExpiryAttribute()
    {
        if ($this->status === 'pending') {
            return max(0, Carbon::today()->diffInDays($this->expiry_date, false));
        }
        return 0;
    }
}
