<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasableBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_sach',
        'tac_gia',
        'mo_ta',
        'hinh_anh',
        'gia',
        'nha_xuat_ban',
        'nam_xuat_ban',
        'isbn',
        'so_trang',
        'ngon_ngu',
        'dinh_dang',
        'kich_thuoc_file',
        'trang_thai',
        'so_luong_ban',
        'so_luong_ton',
        'danh_gia_trung_binh',
        'so_luot_xem',
    ];

    protected $casts = [
        'gia' => 'decimal:2',
        'danh_gia_trung_binh' => 'decimal:2',
        'nam_xuat_ban' => 'integer',
        'so_trang' => 'integer',
        'kich_thuoc_file' => 'integer',
        'so_luong_ban' => 'integer',
        'so_luong_ton' => 'integer',
        'so_luot_xem' => 'integer',
    ];

    // Scope để lấy sách đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    // Scope để sắp xếp theo giá
    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('gia', $direction);
    }

    // Scope để sắp xếp theo đánh giá
    public function scopeOrderByRating($query, $direction = 'desc')
    {
        return $query->orderBy('danh_gia_trung_binh', $direction);
    }

    // Scope để sắp xếp theo lượt bán
    public function scopeOrderBySales($query, $direction = 'desc')
    {
        return $query->orderBy('so_luong_ban', $direction);
    }

    // Format giá tiền
    public function getFormattedPriceAttribute()
    {
        return number_format($this->gia, 0, ',', '.') . ' VNĐ';
    }

    // Format kích thước file
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->kich_thuoc_file) return null;
        
        $size = $this->kich_thuoc_file;
        $units = ['KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    // Tăng lượt xem
    public function incrementViews()
    {
        $this->increment('so_luot_xem');
    }

    // Tăng số lượng bán
    public function incrementSales()
    {
        $this->increment('so_luong_ban');
    }

    // Kiểm tra sách có còn hàng không
    public function isInStock()
    {
        return $this->so_luong_ton > 0;
    }

    // Giảm số lượng tồn kho
    public function decreaseStock($quantity = 1)
    {
        $this->decrement('so_luong_ton', $quantity);
    }

    // Tăng số lượng tồn kho
    public function increaseStock($quantity = 1)
    {
        $this->increment('so_luong_ton', $quantity);
    }

    // Kiểm tra user đã mua sách này chưa
    public function isPurchasedBy($userId)
    {
        return OrderItem::whereHas('order', function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->whereIn('status', ['processing', 'shipped', 'delivered'])
                  ->whereIn('payment_status', ['paid']);
        })->where('purchasable_book_id', $this->id)->exists();
    }

    // Relationship với OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
