<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Librarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ho_ten',
        'ma_thu_thu',
        'email',
        'so_dien_thoai',
        'ngay_sinh',
        'gioi_tinh',
        'dia_chi',
        'chuc_vu',
        'phong_ban',
        'ngay_vao_lam',
        'ngay_het_han_hop_dong',
        'luong_co_ban',
        'trang_thai',
        'anh_dai_dien',
        'bang_cap',
        'kinh_nghiem',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'ngay_vao_lam' => 'date',
        'ngay_het_han_hop_dong' => 'date',
        'luong_co_ban' => 'decimal:2',
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship với các giao dịch mượn sách
    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'thu_thu_id');
    }

    // Relationship với các phạt
    public function fines()
    {
        return $this->hasMany(Fine::class, 'created_by');
    }

    // Relationship với các đặt trước
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'processed_by');
    }

    // Scope để lấy thủ thư đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    // Scope để lấy thủ thư theo chức vụ
    public function scopeByPosition($query, $position)
    {
        return $query->where('chuc_vu', $position);
    }

    // Scope để lấy thủ thư theo phòng ban
    public function scopeByDepartment($query, $department)
    {
        return $query->where('phong_ban', $department);
    }

    // Kiểm tra hợp đồng có hết hạn không
    public function isContractExpired()
    {
        return $this->ngay_het_han_hop_dong && $this->ngay_het_han_hop_dong < now();
    }

    // Kiểm tra hợp đồng sắp hết hạn (trong 30 ngày)
    public function isContractExpiringSoon()
    {
        return $this->ngay_het_han_hop_dong && 
               $this->ngay_het_han_hop_dong->diffInDays(now()) <= 30 &&
               $this->ngay_het_han_hop_dong > now();
    }

    // Format ngày sinh
    public function getFormattedBirthdayAttribute()
    {
        return $this->ngay_sinh ? $this->ngay_sinh->format('d/m/Y') : 'Chưa cập nhật';
    }

    // Format ngày vào làm
    public function getFormattedStartDateAttribute()
    {
        return $this->ngay_vao_lam ? $this->ngay_vao_lam->format('d/m/Y') : 'Chưa cập nhật';
    }

    // Format ngày hết hạn hợp đồng
    public function getFormattedContractEndDateAttribute()
    {
        return $this->ngay_het_han_hop_dong ? $this->ngay_het_han_hop_dong->format('d/m/Y') : 'Không xác định';
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

    // Tính tuổi
    public function getAgeAttribute()
    {
        if (!$this->ngay_sinh) return null;
        return $this->ngay_sinh->age;
    }

    // Tính số năm kinh nghiệm
    public function getExperienceYearsAttribute()
    {
        if (!$this->ngay_vao_lam) return 0;
        return $this->ngay_vao_lam->diffInYears(now());
    }

    // Lấy số lượng giao dịch mượn sách
    public function getBorrowsCountAttribute()
    {
        return $this->borrows()->count();
    }

    // Lấy số lượng phạt đã tạo
    public function getFinesCountAttribute()
    {
        return $this->fines()->count();
    }
}

















