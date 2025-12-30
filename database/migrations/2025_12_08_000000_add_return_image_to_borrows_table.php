<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            if (!Schema::hasColumn('borrows', 'anh_hoan_tra')) {
                // Sử dụng DB::statement trực tiếp để tránh lỗi với after() và comment()
                $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
                if (!empty($checkTinhTrang)) {
                    DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng' AFTER `tinh_trang_sach`");
                } else {
                    DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            if (Schema::hasColumn('borrows', 'anh_hoan_tra')) {
                $table->dropColumn('anh_hoan_tra');
            }
        });
    }
};
