<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm 2 trạng thái mới: 'cho_duyet' và 'chua_hoan_tat'
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('cho_duyet', 'chua_nhan','Dang muon', 'Da tra', 'Qua han', 'Mat sach' )
            NOT NULL DEFAULT 'Dang muon'
        ");
    }

    public function down(): void
    {
        // Rollback về enum cũ
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Dang muon', 'Da tra', 'Qua han', 'Mat sach')
            NOT NULL DEFAULT 'Dang muon'
        ");
    }
};
