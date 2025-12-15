<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_khoa',
        'ma_khoa',
        'mo_ta',
        'truong_khoa',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'website',
        'ngay_thanh_lap',
        'trang_thai',
        'logo',
    ];

    protected $casts = [
        'ngay_thanh_lap' => 'date',
    ];

    // Relationship với ngành
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    // Relationship với sinh viên (qua Reader)
    public function readers()
    {
        return $this->hasMany(Reader::class);
    }

    // Scope để lấy khoa đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    // Scope để lấy khoa có ngành
    public function scopeWithDepartments($query)
    {
        return $query->has('departments');
    }

    // Scope để sắp xếp theo số lượng ngành
    public function scopeOrderByDepartmentsCount($query, $direction = 'desc')
    {
        return $query->withCount('departments')->orderBy('departments_count', $direction);
    }

    // Kiểm tra khoa có thể xóa không
    public function canDelete()
    {
        return $this->departments()->count() === 0 && $this->readers()->count() === 0;
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

    // Lấy số lượng ngành
    public function getDepartmentsCountAttribute()
    {
        return $this->departments()->count();
    }

    // Lấy số lượng sinh viên
    public function getStudentsCountAttribute()
    {
        return $this->readers()->count();
    }
}

























