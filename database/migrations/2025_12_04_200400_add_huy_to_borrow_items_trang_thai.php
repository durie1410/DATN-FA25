<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm 'Huy' vào ENUM của cột trang_thai trong bảng borrow_items
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Cho duyet', 'Chua nhan', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach', 'Hong', 'Huy')
            NOT NULL DEFAULT 'Cho duyet'
        ");
        
        echo "✓ Đã thêm trạng thái 'Huy' vào borrow_items.trang_thai\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cập nhật các bản ghi có trang_thai = 'Huy' về 'Cho duyet' trước khi xóa
        DB::table('borrow_items')
            ->where('trang_thai', 'Huy')
            ->update(['trang_thai' => 'Cho duyet']);
            
        // Rollback - xóa 'Huy' khỏi ENUM
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Cho duyet', 'Chua nhan', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach', 'Hong')
            NOT NULL DEFAULT 'Cho duyet'
        ");
    }
};

