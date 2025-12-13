<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailedStatusToBorrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrows', function (Blueprint $table) {
            // Thêm cột trạng thái chi tiết
            $table->enum('trang_thai_chi_tiet', [
                'cho_xu_ly',           // Chờ xử lý
                'dang_chuan_bi',       // Đang chuẩn bị hàng
                'dang_giao',           // Đang giao hàng
                'da_giao_thanh_cong',  // Đã giao thành công
                'giao_that_bai',       // Giao thất bại
                'tra_lai_sach',        // Trả lại sách
                'dang_gui_lai',        // Đang gửi lại sách
                'da_nhan_hang',        // Nhận hàng
                'dang_kiem_tra',       // Kiểm tra hàng
                'thanh_toan_coc',      // Thanh toán cọc
                'hoan_thanh'           // Hoàn thành
            ])->default('cho_xu_ly')->after('trang_thai');
            
            // Thêm các cột liên quan đến kiểm tra sách
            $table->enum('tinh_trang_sach', ['binh_thuong', 'hong_nhe', 'hong_nang', 'mat_sach'])
                  ->nullable()
                  ->after('trang_thai_chi_tiet')
                  ->comment('Tình trạng sách khi trả lại');
            
            // Thêm cột phí hỏng sách
            $table->decimal('phi_hong_sach', 10, 2)
                  ->default(0)
                  ->after('tinh_trang_sach')
                  ->comment('Phí phạt nếu sách bị hỏng');
            
            // Thêm cột số tiền cọc thực tế hoàn trả
            $table->decimal('tien_coc_hoan_tra', 10, 2)
                  ->nullable()
                  ->after('phi_hong_sach')
                  ->comment('Số tiền cọc thực tế hoàn trả = tien_coc - phi_hong_sach');
            
            // Thêm timestamp cho các bước
            $table->timestamp('ngay_chuan_bi')->nullable();
            $table->timestamp('ngay_bat_dau_giao')->nullable();
            $table->timestamp('ngay_giao_thanh_cong')->nullable();
            $table->timestamp('ngay_bat_dau_tra')->nullable();
            $table->timestamp('ngay_nhan_tra')->nullable();
            $table->timestamp('ngay_kiem_tra')->nullable();
            $table->timestamp('ngay_hoan_coc')->nullable();
            
            // Thêm ghi chú cho từng bước
            $table->text('ghi_chu_giao_hang')->nullable()->comment('Ghi chú khi giao hàng');
            $table->text('ghi_chu_tra_hang')->nullable()->comment('Ghi chú khi nhận trả hàng');
            $table->text('ghi_chu_kiem_tra')->nullable()->comment('Ghi chú khi kiểm tra sách');
            $table->text('ghi_chu_hoan_coc')->nullable()->comment('Ghi chú khi hoàn cọc');
            
            // Thêm cột người xử lý từng bước
            $table->unsignedBigInteger('nguoi_chuan_bi_id')->nullable();
            $table->unsignedBigInteger('nguoi_giao_hang_id')->nullable();
            $table->unsignedBigInteger('nguoi_kiem_tra_id')->nullable();
            $table->unsignedBigInteger('nguoi_hoan_coc_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropColumn([
                'trang_thai_chi_tiet',
                'tinh_trang_sach',
                'phi_hong_sach',
                'tien_coc_hoan_tra',
                'ngay_chuan_bi',
                'ngay_bat_dau_giao',
                'ngay_giao_thanh_cong',
                'ngay_bat_dau_tra',
                'ngay_nhan_tra',
                'ngay_kiem_tra',
                'ngay_hoan_coc',
                'ghi_chu_giao_hang',
                'ghi_chu_tra_hang',
                'ghi_chu_kiem_tra',
                'ghi_chu_hoan_coc',
                'nguoi_chuan_bi_id',
                'nguoi_giao_hang_id',
                'nguoi_kiem_tra_id',
                'nguoi_hoan_coc_id',
            ]);
        });
    }
}
