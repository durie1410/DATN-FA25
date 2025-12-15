<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixBorrowsTrangThaiEnumFinal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Cập nhật giá trị cũ không hợp lệ trước
        DB::table('borrows')
            ->where('trang_thai', 'chua_hoan_tat')
            ->update(['trang_thai' => 'Cho duyet']);
        
        // Cập nhật ENUM của cột trang_thai (loại bỏ 'chua_hoan_tat')
        DB::statement("ALTER TABLE borrows MODIFY COLUMN trang_thai ENUM('Cho duyet', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach', 'Huy', 'Hong') DEFAULT 'Cho duyet'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Trở về ENUM cũ
        DB::statement("ALTER TABLE borrows MODIFY COLUMN trang_thai ENUM('Cho duyet', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach', 'Huy') DEFAULT 'Dang muon'");
    }
}
