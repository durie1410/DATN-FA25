<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DropUnusedApiTables extends Migration
{
    /**
     * Run the migrations.
     * Xóa các bảng chỉ được sử dụng trong API nhưng không được sử dụng trong web app chính
     *
     * @return void
     */
    public function up()
    {
        // Tắt kiểm tra foreign key tạm thời để tránh lỗi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Xóa các bảng con trước (có foreign keys)
        // Các bảng này chỉ được sử dụng trong API controllers, không được sử dụng trong web app chính
        Schema::dropIfExists('loan_items');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('seat_reservations');
        Schema::dropIfExists('book_items');
        Schema::dropIfExists('stock_movements');
        
        // Xóa các bảng cha sau
        Schema::dropIfExists('loans');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('spaces');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('addresses');
        // Lưu ý: user_verifications KHÔNG xóa vì được sử dụng trong PublicBookController
        Schema::dropIfExists('pricing_rules');
        
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
        // Không cần rollback vì các bảng này không được sử dụng trong web app chính
        // Nếu cần rollback, có thể chạy lại các migrations gốc
    }
}
