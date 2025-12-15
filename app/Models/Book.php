<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CacheService;
use Illuminate\Support\Facades\Storage;

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
        'trang_thai',
        'danh_gia_trung_binh',
        'so_luong_ban',
        'so_luot_xem',
        'so_luong',
        'is_featured',
       'loai_sach'

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'nha_xuat_ban_id');
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


    // Relationship với BorrowItem
    public function borrowItems()
    {
        return $this->hasMany(BorrowItem::class, 'book_id');
    }

    // Relationship với Borrow thông qua BorrowItem (hasManyThrough)
    // Book -> BorrowItem -> Borrow
    public function borrows()
    {
        return $this->hasManyThrough(
            Borrow::class,      // Model đích (Borrow)
            BorrowItem::class,  // Model trung gian (BorrowItem)
            'book_id',          // Foreign key trên BorrowItem trỏ tới Book
            'id',               // Local key trên Borrow (id)
            'id',               // Local key trên Book (id)
            'borrow_id'         // Foreign key trên BorrowItem trỏ tới Borrow
        );
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

    // Get image URL - simple and reliable
    public function getImageUrlAttribute()
    {
        if (!$this->hinh_anh) {
            return null;
        }

        // Check if it's already a full URL
        if (filter_var($this->hinh_anh, FILTER_VALIDATE_URL)) {
            return $this->hinh_anh;
        }

        // Clean path - normalize slashes and remove leading slashes
        $path = ltrim(str_replace(['\\', '//'], '/', $this->hinh_anh), '/');
        
        // Use asset() - most reliable and consistent way
        return asset('storage/' . $path);
    }

    // Get image URL with fallback
    public function getImageUrlOrPlaceholderAttribute()
    {
        $url = $this->image_url;
        if ($url && Storage::disk('public')->exists($this->hinh_anh)) {
            return $url;
        }
        return asset('images/placeholder-book.png'); // Placeholder image path
    }

    // Scope để lấy sách đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    // Kiểm tra sách có thể đặt trước không
    public function canBeReserved()
    {
        // Sách có thể đặt trước nếu:
        // 1. Sách đang hoạt động
        // 2. Có ít nhất một bản copy trong kho (có thể đặt trước ngay cả khi tất cả đang được mượn)
        return $this->trang_thai === 'active' && $this->total_copies > 0;
    }

    // Scope để lấy sách có thể đặt trước
    public function scopeCanBeReserved($query)
    {
        return $query->where('trang_thai', 'active')
            ->whereHas('inventories');
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        // Clear cache when book is created, updated, or deleted
        static::created(function ($book) {
            CacheService::clearDashboard();
        });

        static::updated(function ($book) {
            CacheService::clearDashboard();
        });

        static::deleted(function ($book) {
            CacheService::clearDashboard();
        });
    }
}
