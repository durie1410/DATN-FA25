<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CacheService;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_nha_xuat_ban',
        'dia_chi',
        'so_dien_thoai',
        'email',
        'website',
        'mo_ta',
        'ngay_thanh_lap',
        'trang_thai',
        'logo',
    ];

    protected $casts = [
        'ngay_thanh_lap' => 'date',
    ];

    // Relationship với sách
    public function books()
    {
        return $this->hasMany(Book::class, 'nha_xuat_ban_id');
    }

    // Relationship với sách có thể mua
    public function purchasableBooks()
    {
        return $this->hasMany(PurchasableBook::class, 'nha_xuat_ban_id');
    }

    // Scope để lấy nhà xuất bản đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    // Scope để lấy nhà xuất bản có sách
    public function scopeWithBooks($query)
    {
        return $query->has('books');
    }

    // Scope để sắp xếp theo số lượng sách
    public function scopeOrderByBooksCount($query, $direction = 'desc')
    {
        return $query->withCount('books')->orderBy('books_count', $direction);
    }

    // Kiểm tra nhà xuất bản có thể xóa không
    public function canDelete()
    {
        return $this->books()->count() === 0;
    }

    // Format ngày thành lập
    public function getFormattedFoundedDateAttribute()
    {
        return $this->ngay_thanh_lap ? $this->ngay_thanh_lap->format('d/m/Y') : 'Chưa cập nhật';
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

    // Lấy số lượng sách
    public function getBooksCountAttribute()
    {
        return $this->books()->count();
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        // Clear cache when publisher is created, updated, or deleted
        static::created(function ($publisher) {
            CacheService::clearPublishers();
            CacheService::clearDashboard();
        });

        static::updated(function ($publisher) {
            CacheService::clearPublishers();
            CacheService::clearDashboard();
        });

        static::deleted(function ($publisher) {
            CacheService::clearPublishers();
            CacheService::clearDashboard();
        });
    }
}

























