<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_nganh',
        'ma_nganh',
        'faculty_id',
        'mo_ta',
        'truong_nganh',
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

    // Relationship với khoa
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    // Relationship với sinh viên (qua Reader)
    public function readers()
    {
        return $this->hasMany(Reader::class);
    }

    // Scope để lấy ngành đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    // Scope để lấy ngành theo khoa
    public function scopeByFaculty($query, $facultyId)
    {
        return $query->where('faculty_id', $facultyId);
    }

    // Scope để sắp xếp theo số lượng sinh viên
    public function scopeOrderByStudentsCount($query, $direction = 'desc')
    {
        return $query->withCount('readers')->orderBy('readers_count', $direction);
    }

    // Kiểm tra ngành có thể xóa không
    public function canDelete()
    {
        return $this->readers()->count() === 0;
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

    // Lấy số lượng sinh viên
    public function getStudentsCountAttribute()
    {
        return $this->readers()->count();
    }

    // Lấy tên khoa
    public function getFacultyNameAttribute()
    {
        return $this->faculty ? $this->faculty->ten_khoa : 'Chưa phân khoa';
    }
}

























