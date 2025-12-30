<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateBorrowStatusSystemTo11Steps extends Migration
{
    /**
     * Run the migrations.
     * Cập nhật hệ thống trạng thái đơn mượn thành 11 bước theo quy trình vận chuyển
     *
     * @return void
     */
    public function up()
    {
        // Kiểm tra xem cột trang_thai_chi_tiet có tồn tại không
        if (!Schema::hasColumn('borrows', 'trang_thai_chi_tiet')) {
            // Nếu chưa có, tạo cột mới luôn với 11 trạng thái
            DB::statement("
                ALTER TABLE borrows 
                ADD COLUMN trang_thai_chi_tiet 
                ENUM(
                    'don_hang_moi',
                    'dang_chuan_bi_sach',
                    'cho_ban_giao_van_chuyen',
                    'dang_giao_hang',
                    'giao_hang_thanh_cong',
                    'giao_hang_that_bai',
                    'da_muon_dang_luu_hanh',
                    'cho_tra_sach',
                    'dang_van_chuyen_tra_ve',
                    'da_nhan_va_kiem_tra',
                    'hoan_tat_don_hang'
                ) 
                DEFAULT 'don_hang_moi' NOT NULL
                AFTER trang_thai
            ");
        } else {
            // Nếu đã có, map dữ liệu cũ sang mới
            $mapping = [
                'cho_xu_ly' => 'don_hang_moi',
                'dang_chuan_bi' => 'dang_chuan_bi_sach',
                'dang_giao' => 'dang_giao_hang',
                'da_giao_thanh_cong' => 'giao_hang_thanh_cong',
                'giao_that_bai' => 'giao_hang_that_bai',
                'tra_lai_sach' => 'cho_tra_sach',
                'dang_gui_lai' => 'dang_van_chuyen_tra_ve',
                'da_nhan_hang' => 'da_nhan_va_kiem_tra',
                'dang_kiem_tra' => 'da_nhan_va_kiem_tra',
                'thanh_toan_coc' => 'da_nhan_va_kiem_tra',
                'hoan_thanh' => 'hoan_tat_don_hang',
            ];

            // Chuyển sang VARCHAR để update
            DB::statement("ALTER TABLE borrows MODIFY COLUMN trang_thai_chi_tiet VARCHAR(50) DEFAULT 'don_hang_moi'");

            // Update dữ liệu
            foreach ($mapping as $oldValue => $newValue) {
                DB::table('borrows')
                    ->where('trang_thai_chi_tiet', $oldValue)
                    ->update(['trang_thai_chi_tiet' => $newValue]);
            }

            // Chuyển lại sang ENUM với 11 trạng thái mới
            DB::statement("
                ALTER TABLE borrows 
                MODIFY COLUMN trang_thai_chi_tiet 
                ENUM(
                    'don_hang_moi',
                    'dang_chuan_bi_sach',
                    'cho_ban_giao_van_chuyen',
                    'dang_giao_hang',
                    'giao_hang_thanh_cong',
                    'giao_hang_that_bai',
                    'da_muon_dang_luu_hanh',
                    'cho_tra_sach',
                    'dang_van_chuyen_tra_ve',
                    'da_nhan_va_kiem_tra',
                    'hoan_tat_don_hang'
                ) 
                DEFAULT 'don_hang_moi' NOT NULL
            ");
        }

        // Thêm các cột timestamp mới nếu chưa có (an toàn với IF NOT EXISTS thông qua Schema)
        Schema::table('borrows', function (Blueprint $table) {
            // Timestamp cho từng bước
            if (!Schema::hasColumn('borrows', 'ngay_xac_nhan')) {
                $table->timestamp('ngay_xac_nhan')->nullable()->comment('Ngày xác nhận đơn hàng');
            }
            if (!Schema::hasColumn('borrows', 'ngay_dong_goi_xong')) {
                $table->timestamp('ngay_dong_goi_xong')->nullable()->comment('Ngày đóng gói xong');
            }
            if (!Schema::hasColumn('borrows', 'ngay_ban_giao_van_chuyen')) {
                $table->timestamp('ngay_ban_giao_van_chuyen')->nullable()->comment('Ngày bàn giao cho đơn vị vận chuyển');
            }
            if (!Schema::hasColumn('borrows', 'ngay_that_bai_giao_hang')) {
                $table->timestamp('ngay_that_bai_giao_hang')->nullable()->comment('Ngày giao hàng thất bại');
            }
            if (!Schema::hasColumn('borrows', 'ngay_bat_dau_luu_hanh')) {
                $table->timestamp('ngay_bat_dau_luu_hanh')->nullable()->comment('Ngày bắt đầu lưu hành (người mượn giữ sách)');
            }
            if (!Schema::hasColumn('borrows', 'ngay_yeu_cau_tra_sach')) {
                $table->timestamp('ngay_yeu_cau_tra_sach')->nullable()->comment('Ngày người mượn yêu cầu trả sách');
            }
            
            // Ghi chú cho các trạng thái mới
            if (!Schema::hasColumn('borrows', 'ghi_chu_dong_goi')) {
                $table->text('ghi_chu_dong_goi')->nullable()->comment('Ghi chú khi đóng gói sách');
            }
            if (!Schema::hasColumn('borrows', 'ghi_chu_ban_giao')) {
                $table->text('ghi_chu_ban_giao')->nullable()->comment('Ghi chú khi bàn giao vận chuyển');
            }
            if (!Schema::hasColumn('borrows', 'ghi_chu_that_bai')) {
                $table->text('ghi_chu_that_bai')->nullable()->comment('Ghi chú khi giao hàng thất bại');
            }
            if (!Schema::hasColumn('borrows', 'ghi_chu_yeu_cau_tra')) {
                $table->text('ghi_chu_yeu_cau_tra')->nullable()->comment('Ghi chú yêu cầu trả sách');
            }
            
            // Mã vận đơn
            if (!Schema::hasColumn('borrows', 'ma_van_don_di')) {
                $table->string('ma_van_don_di', 100)->nullable()->comment('Mã vận đơn khi giao đi');
            }
            if (!Schema::hasColumn('borrows', 'ma_van_don_ve')) {
                $table->string('ma_van_don_ve', 100)->nullable()->comment('Mã vận đơn khi trả về');
            }
            if (!Schema::hasColumn('borrows', 'don_vi_van_chuyen')) {
                $table->string('don_vi_van_chuyen', 100)->nullable()->comment('Đơn vị vận chuyển (ViettelPost, GHN, GHTK...)');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Khôi phục lại enum cũ (10 trạng thái cũ)
        DB::statement("
            ALTER TABLE borrows 
            MODIFY COLUMN trang_thai_chi_tiet 
            ENUM(
                'cho_xu_ly',
                'dang_chuan_bi',
                'dang_giao',
                'da_giao_thanh_cong',
                'giao_that_bai',
                'tra_lai_sach',
                'dang_gui_lai',
                'da_nhan_hang',
                'dang_kiem_tra',
                'thanh_toan_coc',
                'hoan_thanh'
            ) 
            DEFAULT 'cho_xu_ly'
        ");

        // Xóa các cột mới đã thêm
        Schema::table('borrows', function (Blueprint $table) {
            $columns = [
                'ngay_xac_nhan',
                'ngay_dong_goi_xong',
                'ngay_ban_giao_van_chuyen',
                'ngay_that_bai_giao_hang',
                'ngay_bat_dau_luu_hanh',
                'ngay_yeu_cau_tra_sach',
                'ghi_chu_dong_goi',
                'ghi_chu_ban_giao',
                'ghi_chu_that_bai',
                'ghi_chu_yeu_cau_tra',
                'ma_van_don_di',
                'ma_van_don_ve',
                'don_vi_van_chuyen'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('borrows', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
