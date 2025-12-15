<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDepositManagementFieldsToBorrowItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrow_items', function (Blueprint $table) {
            // Thêm các trường quản lý tiền cọc
            $table->date('ngay_thu_coc')->nullable()->after('trang_thai_coc')
                  ->comment('Ngày thu tiền cọc');
            
            $table->date('ngay_hoan_coc')->nullable()->after('ngay_thu_coc')
                  ->comment('Ngày hoàn tiền cọc');
            
            $table->decimal('tien_coc_da_thu', 10, 2)->default(0.00)->after('tien_coc')
                  ->comment('Số tiền cọc đã thu (có thể thu từng phần)');
            
            $table->decimal('tien_coc_da_hoan', 10, 2)->default(0.00)->after('tien_coc_da_thu')
                  ->comment('Số tiền cọc đã hoàn');
            
            $table->enum('phuong_thuc_thu_coc', [
                'tien_mat',      // Tiền mặt
                'chuyen_khoan',  // Chuyển khoản
                'online',        // Online (MOMO, VNPAY, etc.)
                'khac'           // Khác
            ])->nullable()->after('tien_coc_da_hoan')
              ->comment('Phương thức thu tiền cọc');
            
            $table->enum('phuong_thuc_hoan_coc', [
                'tien_mat',      // Tiền mặt
                'chuyen_khoan',  // Chuyển khoản
                'online',        // Online (MOMO, VNPAY, etc.)
                'tru_vao_phat',  // Trừ vào tiền phạt
                'khac'           // Khác
            ])->nullable()->after('phuong_thuc_thu_coc')
              ->comment('Phương thức hoàn tiền cọc');
            
            $table->text('ghi_chu_coc')->nullable()->after('phuong_thuc_hoan_coc')
                  ->comment('Ghi chú về tiền cọc');
        });

        // Cập nhật enum trang_thai_coc để thêm trạng thái 'tru_vao_phat'
        // Laravel không hỗ trợ modify enum trực tiếp, nên dùng DB::statement
        DB::statement("ALTER TABLE borrow_items MODIFY COLUMN trang_thai_coc ENUM('cho_xu_ly', 'da_thu', 'da_hoan', 'tru_vao_phat') DEFAULT 'cho_xu_ly' COMMENT 'Trạng thái tiền cọc: chờ xử lý, đã thu, đã hoàn, trừ vào phạt'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrow_items', function (Blueprint $table) {
            // Xóa các trường đã thêm
            $table->dropColumn([
                'ngay_thu_coc',
                'ngay_hoan_coc',
                'tien_coc_da_thu',
                'tien_coc_da_hoan',
                'phuong_thuc_thu_coc',
                'phuong_thuc_hoan_coc',
                'ghi_chu_coc'
            ]);
        });

        // Khôi phục enum trang_thai_coc về trạng thái ban đầu
        DB::statement("ALTER TABLE borrow_items MODIFY COLUMN trang_thai_coc ENUM('cho_xu_ly', 'da_thu', 'da_hoan') DEFAULT 'cho_xu_ly' COMMENT 'Trạng thái tiền cọc: chờ xử lý, đã thu, đã hoàn'");
    }
}
