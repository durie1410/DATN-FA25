<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 👈 thêm dòng này

class Voucher extends Model
{
    use HasFactory, SoftDeletes; // 👈 thêm SoftDeletes vào đây

    protected $table = 'vouchers';

    protected $fillable = [
        'reader_id',
        'ma',
        'loai',
        'gia_tri',
        'so_luong',
        'mo_ta',
        'don_toi_thieu',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'kich_hoat',
        'trang_thai',
    ];

    // Mối quan hệ tới reader (nếu voucher gắn với độc giả cụ thể)
    public function reader()
    {
        return $this->belongsTo(Reader::class, 'reader_id');
    }

    // ========================
    // 🧮 Các hàm tiện ích
    // ========================

    // Kiểm tra voucher còn hiệu lực không
    public function isActive()
    {
        $today = now()->toDateString();

        return $this->kich_hoat == 1
            && $this->trang_thai === 'active'
            && ($this->ngay_bat_dau <= $today && $this->ngay_ket_thuc >= $today);
    }

    // Hiển thị loại giảm giá dễ hiểu
    public function getTypeLabelAttribute()
    {
        return $this->loai === 'percentage' ? 'Giảm theo %' : 'Giảm tiền cố định';
    }
}
