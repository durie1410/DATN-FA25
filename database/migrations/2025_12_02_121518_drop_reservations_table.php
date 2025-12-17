<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DropReservationsTable extends Migration
{
    /**
     * Run the migrations.
     * Xóa bảng reservations và tất cả chức năng liên quan
     *
     * @return void
     */
    public function up()
    {
        // Tắt kiểm tra foreign key tạm thời
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Xóa bảng reservations
        Schema::dropIfExists('reservations');
        
        // Bật lại kiểm tra foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Không cần rollback vì bảng này không còn được sử dụng
    }
}
