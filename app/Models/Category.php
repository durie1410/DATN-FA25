<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_the_loai',
        'mo_ta',
        'trang_thai',
        'mau_sac',
        'icon',
    ];

    protected $casts = [
        'trang_thai' => 'string',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // Scope để lấy thể loại đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    // Scope để lấy thể loại có sách
    public function scopeWithBooks($query)
    {
        return $query->has('books');
    }

    // Scope để lấy thể loại không có sách
    public function scopeWithoutBooks($query)
    {
        return $query->doesntHave('books');
    }

    // Scope để sắp xếp theo số lượng sách
    public function scopeOrderByBooksCount($query, $direction = 'desc')
    {
        return $query->withCount('books')->orderBy('books_count', $direction);
    }

    // Kiểm tra thể loại có thể xóa không
    public function canDelete()
    {
        return $this->books()->count() === 0;
    }

    // Lấy màu sắc mặc định nếu không có
    public function getColorAttribute()
    {
        return $this->mau_sac ?: '#007bff';
    }

    // Lấy icon mặc định nếu không có
    public function getIconAttribute($value)
    {
        return $value ?: 'fas fa-folder';
    }
}
