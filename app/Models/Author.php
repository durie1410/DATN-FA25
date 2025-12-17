<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_tac_gia',
        'email',
        'so_dien_thoai',
        'dia_chi',
        'ngay_sinh',
        'gioi_thieu',
        'hinh_anh',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
    ];

    // Scope để lấy tác giả đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    // Relationship với sách
    public function books()
    {
        return $this->hasMany(Book::class, 'tac_gia', 'ten_tac_gia');
    }

    // Relationship với sách có thể mua
    public function purchasableBooks()
    {
        return $this->hasMany(PurchasableBook::class, 'tac_gia', 'ten_tac_gia');
    }

    // Format ngày sinh
    public function getFormattedBirthdayAttribute()
    {
        return $this->ngay_sinh ? $this->ngay_sinh->format('d/m/Y') : 'Chưa cập nhật';
    }

    // Tính tuổi
    public function getAgeAttribute()
    {
        if (!$this->ngay_sinh) return null;
        return $this->ngay_sinh->age;
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
}