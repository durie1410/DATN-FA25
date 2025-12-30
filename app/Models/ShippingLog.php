<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingLog extends Model
{
    protected $table = 'shipping_logs';

    protected $fillable = [
        'borrow_id',
        'borrow_item_id',
        'status',
        'tinh_trang_sach',
        'phi_hong_sach',
        'tien_coc_hoan_tra',
        'receiver_note',
        'shipper_note',
        'proof_image',
        'delivered_at',
        'shipper_name',
        'shipper_phone',
        'ngay_chuan_bi',
        'ngay_dong_goi_xong',
        'ngay_bat_dau_giao',
        'ngay_giao_thanh_cong',
        'ngay_that_bai_giao_hang',
        'ngay_bat_dau_luu_hanh',
        'ngay_yeu_cau_tra_sach',
        'ngay_bat_dau_tra',
        'ngay_nhan_tra',
        'ngay_kiem_tra',
        'ngay_hoan_coc',
        'ghi_chu_kiem_tra',
        'ghi_chu_hoan_coc',
        'nguoi_chuan_bi_id',
        'nguoi_kiem_tra_id',
        'nguoi_hoan_coc_id',
        'ma_van_don',
        'don_vi_van_chuyen',
    ];

    public function borrow()
    {
        return $this->belongsTo(Borrow::class, 'borrow_id');
    }

    public function item()
    {
        return $this->belongsTo(BorrowItem::class, 'borrow_item_id');
    }
    
    // Người chuẩn bị
    public function nguoiChuanBi()
    {
        return $this->belongsTo(User::class, 'nguoi_chuan_bi_id');
    }
    
    // Người kiểm tra
    public function nguoiKiemTra()
    {
        return $this->belongsTo(User::class, 'nguoi_kiem_tra_id');
    }
    
    // Người hoàn cọc
    public function nguoiHoanCoc()
    {
        return $this->belongsTo(User::class, 'nguoi_hoan_coc_id');
    }
    
}

