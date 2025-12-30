<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kiểm tra và thêm cột nếu chưa có
        $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
        
        if (empty($columns)) {
            // Kiểm tra xem cột tinh_trang_sach có tồn tại không
            $checkColumn = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
            
            if (empty($checkColumn)) {
                // Nếu không có tinh_trang_sach, thêm vào cuối
                DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
            } else {
                // Thêm sau tinh_trang_sach - FIX: COMMENT phải đứng trước AFTER
                DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng' AFTER `tinh_trang_sach`");
            }
        }
        
        // Đảm bảo thư mục storage tồn tại
        $storagePath = storage_path('app/public/return-books');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
    }

    public function down(): void
    {
        $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
        if (!empty($columns)) {
            DB::statement("ALTER TABLE `borrows` DROP COLUMN `anh_hoan_tra`");
        }
    }
};
