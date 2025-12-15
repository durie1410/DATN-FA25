<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class VerifyBorrowItemsEnumTrangThai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Đảm bảo enum có đầy đủ các giá trị cần thiết
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Cho duyet', 'Chua nhan', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach', 'Hong')
            NOT NULL DEFAULT 'Cho duyet'
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback về enum trước đó
        DB::statement("
            ALTER TABLE borrow_items
            MODIFY COLUMN trang_thai 
            ENUM('Cho duyet', 'Chua nhan', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach')
            NOT NULL DEFAULT 'Dang muon'
        ");
    }
}
