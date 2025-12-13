<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDetailedStatusToShippingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Bước 1: Thêm ENUM mới bao gồm cả giá trị cũ
        DB::statement("ALTER TABLE shipping_logs MODIFY COLUMN status ENUM(
            'cho_xu_ly',
            'dang_chuan_bi',
            'dang_giao',
            'da_giao',
            'da_giao_thanh_cong',
            'khong_nhan',
            'giao_that_bai',
            'hoan_hang',
            'tra_lai_sach',
            'dang_gui_lai',
            'da_nhan_hang',
            'dang_kiem_tra',
            'thanh_toan_coc',
            'hoan_thanh'
        ) NOT NULL DEFAULT 'cho_xu_ly'");
        
        // Bước 2: Cập nhật dữ liệu cũ sang giá trị mới
        DB::update("UPDATE shipping_logs SET status = 'da_giao_thanh_cong' WHERE status = 'da_giao'");
        DB::update("UPDATE shipping_logs SET status = 'giao_that_bai' WHERE status = 'khong_nhan'");
        DB::update("UPDATE shipping_logs SET status = 'tra_lai_sach' WHERE status = 'hoan_hang'");
        
        // Bước 3: Xóa các giá trị cũ khỏi ENUM
        DB::statement("ALTER TABLE shipping_logs MODIFY COLUMN status ENUM(
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
        ) NOT NULL DEFAULT 'cho_xu_ly'");
        
        Schema::table('shipping_logs', function (Blueprint $table) {
            
            // Thêm các cột liên quan đến kiểm tra và hoàn cọc
            $table->enum('tinh_trang_sach', ['binh_thuong', 'hong_nhe', 'hong_nang', 'mat_sach'])
                  ->nullable()
                  ->after('status')
                  ->comment('Tình trạng sách khi trả lại');
            
            $table->decimal('phi_hong_sach', 10, 2)
                  ->default(0)
                  ->after('tinh_trang_sach')
                  ->comment('Phí phạt nếu sách bị hỏng');
            
            $table->decimal('tien_coc_hoan_tra', 10, 2)
                  ->nullable()
                  ->after('phi_hong_sach')
                  ->comment('Số tiền cọc thực tế hoàn trả');
            
            // Thêm timestamp cho các bước
            $table->timestamp('ngay_chuan_bi')->nullable()->after('tien_coc_hoan_tra');
            $table->timestamp('ngay_bat_dau_giao')->nullable();
            $table->timestamp('ngay_giao_thanh_cong')->nullable();
            $table->timestamp('ngay_bat_dau_tra')->nullable();
            $table->timestamp('ngay_nhan_tra')->nullable();
            $table->timestamp('ngay_kiem_tra')->nullable();
            $table->timestamp('ngay_hoan_coc')->nullable();
            
            // Thêm ghi chú cho từng bước
            $table->text('ghi_chu_kiem_tra')->nullable();
            $table->text('ghi_chu_hoan_coc')->nullable();
            
            // Thêm người xử lý
            $table->unsignedBigInteger('nguoi_chuan_bi_id')->nullable();
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
        Schema::table('shipping_logs', function (Blueprint $table) {
            // Xóa các cột đã thêm
            $table->dropColumn([
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
                'ghi_chu_kiem_tra',
                'ghi_chu_hoan_coc',
                'nguoi_chuan_bi_id',
                'nguoi_kiem_tra_id',
                'nguoi_hoan_coc_id',
            ]);
            
            // Khôi phục enum cũ
            DB::statement("ALTER TABLE shipping_logs MODIFY COLUMN status ENUM(
                'cho_xu_ly',
                'dang_giao',
                'da_giao',
                'khong_nhan',
                'hoan_hang'
            ) NOT NULL DEFAULT 'dang_giao'");
        });
    }
}
