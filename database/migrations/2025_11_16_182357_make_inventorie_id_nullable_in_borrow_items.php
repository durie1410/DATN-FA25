<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeInventorieIdNullableInBorrowItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Đảm bảo inventorie_id có thể null
        DB::statement("
            ALTER TABLE borrow_items 
            MODIFY COLUMN inventorie_id 
            BIGINT UNSIGNED NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Không thể rollback vì có thể có dữ liệu null
        // Nếu cần rollback, phải xóa tất cả records có inventorie_id = null trước
    }
}
