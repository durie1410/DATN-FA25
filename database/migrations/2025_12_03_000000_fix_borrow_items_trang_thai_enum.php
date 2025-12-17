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
        // Đảm bảo enum có đầy đủ các giá trị: Cho duyet, Chua nhan, Dang muon, Da tra, Qua han, Mat sach, Hong
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Cho duyet', 'Chua nhan', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach', 'Hong')
            NOT NULL DEFAULT 'Cho duyet'
        ");
        
        echo "✓ Đã cập nhật enum trang_thai với đầy đủ giá trị\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Cho duyet', 'Chua nhan', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach')
            NOT NULL DEFAULT 'Cho duyet'
        ");
    }
};


