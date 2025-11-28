<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Xóa các bảng không được sử dụng trong codebase
        
        // Các bảng không có model và không được sử dụng trong code
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('violations');
        Schema::dropIfExists('review_likes');
        
        // Các bảng không được sử dụng trong code (không có model, không có DB::table)
        Schema::dropIfExists('reports_cache');
        Schema::dropIfExists('capacity_snapshots');
        Schema::dropIfExists('display_allocations');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Không cần rollback vì các bảng này không được sử dụng
        // Nếu cần rollback, có thể tạo lại migrations gốc
    }
}
