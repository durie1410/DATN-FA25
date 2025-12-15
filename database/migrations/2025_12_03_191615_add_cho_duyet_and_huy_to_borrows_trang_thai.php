<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddChoDuyetAndHuyToBorrowsTrangThai extends Migration
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
            ->update(['trang_thai' => 'Dang muon']);
        
        // Thêm 'Cho duyet' và 'Huy' vào ENUM của cột trang_thai
        DB::statement("ALTER TABLE borrows MODIFY COLUMN trang_thai ENUM('Cho duyet', 'Dang muon', 'Da tra', 'Qua han', 'Mat sach', 'Huy') DEFAULT 'Dang muon'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Trở về ENUM cũ
        DB::statement("ALTER TABLE borrows MODIFY COLUMN trang_thai ENUM('Dang muon', 'Da tra', 'Qua han', 'Mat sach') DEFAULT 'Dang muon'");
    }
}
