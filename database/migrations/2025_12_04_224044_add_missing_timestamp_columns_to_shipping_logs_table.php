<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingTimestampColumnsToShippingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_logs', function (Blueprint $table) {
            // Thêm các cột timestamp còn thiếu cho 11 trạng thái mới
            $table->timestamp('ngay_dong_goi_xong')->nullable()->after('ngay_chuan_bi')
                  ->comment('Ngày đóng gói xong (Trạng thái: Chờ bàn giao vận chuyển)');
            
            $table->timestamp('ngay_that_bai_giao_hang')->nullable()->after('ngay_giao_thanh_cong')
                  ->comment('Ngày giao hàng thất bại');
            
            $table->timestamp('ngay_bat_dau_luu_hanh')->nullable()->after('ngay_that_bai_giao_hang')
                  ->comment('Ngày bắt đầu lưu hành sách (Trạng thái: Đã mượn - Đang lưu hành)');
            
            $table->timestamp('ngay_yeu_cau_tra_sach')->nullable()->after('ngay_bat_dau_luu_hanh')
                  ->comment('Ngày tạo yêu cầu trả sách');
            
            // Thêm thông tin vận đơn
            $table->string('ma_van_don', 100)->nullable()->after('proof_image')
                  ->comment('Mã vận đơn từ đơn vị vận chuyển');
            
            $table->string('don_vi_van_chuyen', 100)->nullable()->after('ma_van_don')
                  ->comment('Tên đơn vị vận chuyển (VD: GHTK, GHN, Viettel Post)');
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
            $table->dropColumn([
                'ngay_dong_goi_xong',
                'ngay_that_bai_giao_hang',
                'ngay_bat_dau_luu_hanh',
                'ngay_yeu_cau_tra_sach',
                'ma_van_don',
                'don_vi_van_chuyen',
            ]);
        });
    }
}
