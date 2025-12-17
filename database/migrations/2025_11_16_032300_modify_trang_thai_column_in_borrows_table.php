<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Giữ lại TẤT CẢ trạng thái cũ + thêm 'cho_duyet' và 'chua_nhan'
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Cho duyet', 'Chua nhan', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach')
            NOT NULL DEFAULT 'Dang muon'
        ");
    }

    public function down(): void
    {
        // Rollback về enum cũ (chỉ giữ 4 trạng thái ban đầu)
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Dang muon', 'Da tra', 'Qua han', 'Mat sach')
            NOT NULL DEFAULT 'Dang muon'
        ");
    }
};