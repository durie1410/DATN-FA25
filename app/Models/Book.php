<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_sach',
        'category_id',
        'nha_xuat_ban_id',
        'tac_gia',
        'nam_xuat_ban',
        'hinh_anh',
        'mo_ta',
        'gia',
        'dinh_dang',
        'trang_thai',
        'danh_gia_trung_binh',
        'so_luong_ban',
        'so_luot_xem',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'nha_xuat_ban_id');
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function verifiedReviews()
    {
        return $this->hasMany(Review::class)->where('is_verified', true);
    }

    // Tính điểm đánh giá trung bình
    public function getAverageRatingAttribute()
    {
        return $this->verifiedReviews()->avg('rating') ?? 0;
    }

    // Đếm số lượng đánh giá
    public function getReviewsCountAttribute()
    {
        return $this->verifiedReviews()->count();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // Kiểm tra sách có được user yêu thích không
    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function activeReservations()
    {
        return $this->hasMany(Reservation::class)->whereIn('status', ['pending', 'confirmed', 'ready']);
    }

    public function pendingReservations()
    {
        return $this->hasMany(Reservation::class)->where('status', 'pending');
    }

    // Kiểm tra sách có sẵn sàng để mượn không
    public function isAvailable()
    {
        $activeBorrows = $this->borrows()->where('trang_thai', 'Dang muon')->count();
        $activeReservations = $this->activeReservations()->count();
        
        // Giả sử mỗi sách có 1 bản copy
        return $activeBorrows === 0 && $activeReservations === 0;
    }

    // Kiểm tra sách có thể đặt trước không
    public function canBeReserved()
    {
        $activeBorrows = $this->borrows()->where('trang_thai', 'Dang muon')->count();
        return $activeBorrows > 0; // Chỉ đặt trước khi sách đang được mượn
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function availableInventories()
    {
        return $this->hasMany(Inventory::class)->where('status', 'Co san');
    }

    public function borrowedInventories()
    {
        return $this->hasMany(Inventory::class)->where('status', 'Dang muon');
    }

    // Đếm tổng số bản copy
    public function getTotalCopiesAttribute()
    {
        return $this->inventories()->count();
    }

    // Đếm số bản có sẵn
    public function getAvailableCopiesAttribute()
    {
        return $this->inventories()->where('status', 'Co san')->count();
    }

    // Đếm số bản đang mượn
    public function getBorrowedCopiesAttribute()
    {
        return $this->inventories()->where('status', 'Dang muon')->count();
    }

    // Format giá tiền
    public function getFormattedPriceAttribute()
    {
        return $this->gia ? number_format($this->gia, 0, ',', '.') . ' VNĐ' : 'Miễn phí';
    }

    // Format trạng thái
    public function getStatusTextAttribute()
    {
        return $this->trang_thai === 'active' ? 'Hoạt động' : 'Tạm dừng';
    }

    // Format trạng thái với badge
    public function getStatusBadgeAttribute()
    {
        $class = $this->trang_thai === 'active' ? 'bg-success' : 'bg-secondary';
        return "<span class='badge {$class}'>{$this->status_text}</span>";
    }

    // Scope để lấy sách đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }
}
