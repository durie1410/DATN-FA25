<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $table = 'borrows';

    protected $fillable = [
        'ten_nguoi_muon',
        'tinh_thanh',
        'huyen',
        'xa',
        'so_nha',
        'so_dien_thoai',
        'reader_id',
        'librarian_id',
        'ngay_muon',
        'trang_thai',
        'trang_thai_chi_tiet', // ✅ thêm trạng thái chi tiết
        'tinh_trang_sach',
        'phi_hong_sach',
        'tien_coc_hoan_tra',
        'tong_tien',
        'tien_coc',
        'tien_thue',
        'tien_ship',
        'voucher_id',
        'ghi_chu',
        'ghi_chu_giao_hang',
        'ghi_chu_tra_hang',
        'ghi_chu_kiem_tra',
        'ghi_chu_hoan_coc',
        'ngay_chuan_bi',
        'ngay_bat_dau_giao',
        'ngay_giao_thanh_cong',
        'ngay_bat_dau_tra',
        'ngay_nhan_tra',
        'ngay_kiem_tra',
        'ngay_hoan_coc',
        'nguoi_chuan_bi_id',
        'nguoi_giao_hang_id',
        'nguoi_kiem_tra_id',
        'nguoi_hoan_coc_id',
    ];

    protected $casts = [
        'ngay_muon' => 'date',
    ];
    

    // 🔹 Một phiếu mượn có nhiều sách mượn
    public function borrowItems()
    {
        return $this->hasMany(BorrowItem::class, 'borrow_id', 'id');
    }

    // 🔹 Lấy quyển sách đầu tiên (nếu cần hiển thị nhanh)
    public function getBookAttribute()
    {
        // Sử dụng eager-loaded items nếu có, nếu không thì query
        if ($this->relationLoaded('items')) {
            return $this->items->first()?->book;
        }
        return $this->items()->first()?->book;
    }

    // 🔹 Lấy ngày trả thực tế (lấy từ borrow_items - item trả đầu tiên)
    public function getNgayTraThucTeAttribute()
    {
        // Sử dụng eager-loaded items nếu có, nếu không thì query
        if ($this->relationLoaded('items')) {
            return $this->items->first()?->ngay_tra_thuc_te;
        }
        return $this->items()->first()?->ngay_tra_thuc_te;
    }

    // 🔹 Lấy ngày hẹn trả (lấy từ borrow_items - item đang mượn đầu tiên)
    public function getNgayHenTraAttribute()
    {
        // Lấy item đang mượn có ngày hẹn trả sớm nhất
        if ($this->relationLoaded('items')) {
            $activeItem = $this->items->where('trang_thai', 'Dang muon')->sortBy('ngay_hen_tra')->first();
            return $activeItem?->ngay_hen_tra;
        }
        $activeItem = $this->items()->where('trang_thai', 'Dang muon')->orderBy('ngay_hen_tra')->first();
        return $activeItem?->ngay_hen_tra;
    }

    // 🔹 Lấy tất cả sách thông qua bảng trung gian BorrowItem
    public function books()
    {
        return $this->hasManyThrough(
            Book::class,
            BorrowItem::class,
            'borrow_id', // FK của BorrowItem trỏ tới Borrow
            'id',        // PK của Book
            'id',        // PK của Borrow
            'book_id'    // FK của BorrowItem trỏ tới Book
        );
    }

    // 🔹 Một Borrow có thể có nhiều BorrowItem
    public function borrowItem()
    {
        return $this->hasOne(BorrowItem::class);
    }

    // 🔹 Người mượn
    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }

    // 🔹 Thủ thư xử lý
    public function librarian()
    {
        return $this->belongsTo(User::class, 'librarian_id');
    }

    // 🔹 Các khoản phạt
    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function pendingFines()
    {
        return $this->hasMany(Fine::class)->where('status', 'pending');
    }

    public function items()
    {
        return $this->hasMany(BorrowItem::class, 'borrow_id');
    }

    // 🔹 Kiểm tra quá hạn
    public function isOverdue()
    {
        return $this->items()
            ->where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', now()->toDateString())
            ->exists();
    }

    // 🔹 Kiểm tra có thể gia hạn không
    public function canExtend()
    {
        $maxExtensions = 2;
        return $this->trang_thai === 'Dang muon' &&
               $this->so_lan_gia_han < $maxExtensions &&
               !$this->isOverdue();
    }

    // 🔹 Gia hạn mượn
    public function extend($days = 7)
    {
        if (!$this->canExtend()) {
            return false;
        }

        // Gia hạn tất cả các item đang mượn
        $this->items()->where('trang_thai', 'Dang muon')->each(function($item) use ($days) {
            $item->extend($days);
        });

        return true;
    }

    // 🔹 Số ngày quá hạn
    public function getDaysOverdueAttribute()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        $ngayHenTra = $this->ngay_hen_tra;
        if (!$ngayHenTra) {
            return 0;
        }
        return now()->diffInDays($ngayHenTra, false);
    }

    // 🔹 Kiểm tra có thể trả sách
    public function canReturn()
    {
        return $this->trang_thai === 'Dang muon';
    }

    // ✅ Thêm quan hệ voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
       
    public function recalculateTotals()
    {
        // Tính tổng các khoản
        $this->tien_coc  = $this->borrowItems()->sum('tien_coc');
        $this->tien_thue = $this->borrowItems()->sum('tien_thue');
        $this->tien_ship = $this->borrowItems()->sum('tien_ship');

        // Tổng trước khi áp dụng voucher
        $this->tong_tien = $this->tien_coc + $this->tien_thue + $this->tien_ship;

        // Nếu có voucher, áp dụng giảm giá
        if ($this->voucher) {
            $voucher = $this->voucher;

            if ($voucher->loai === 'phan_tram') {
                $discount = $this->tong_tien * $voucher->gia_tri / 100;
            } else { // loai = 'tien_mat'
                $discount = $voucher->gia_tri;
            }

            $this->tong_tien = max(0, $this->tong_tien - $discount);
        }

        // Lưu lại vào database
        $this->save();
    }
  

public function payments() {
    return $this->hasMany(BorrowPayment::class);
}

public function shippingLogs() {
    return $this->hasMany(ShippingLog::class);
}

// 🔹 Người chuẩn bị hàng
public function nguoiChuanBi()
{
    return $this->belongsTo(User::class, 'nguoi_chuan_bi_id');
}

// 🔹 Người giao hàng
public function nguoiGiaoHang()
{
    return $this->belongsTo(User::class, 'nguoi_giao_hang_id');
}

// 🔹 Người kiểm tra
public function nguoiKiemTra()
{
    return $this->belongsTo(User::class, 'nguoi_kiem_tra_id');
}

// 🔹 Người hoàn cọc
public function nguoiHoanCoc()
{
    return $this->belongsTo(User::class, 'nguoi_hoan_coc_id');
}

}
