<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DropUnusedBookRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Xóa các bảng không được sử dụng trong codebase
        
        // Bảng payments không được sử dụng (hệ thống sử dụng borrow_payments thay thế)
        Schema::dropIfExists('payments');
        
        // Bảng report_templates: có dữ liệu trong seeder nhưng không có model, không có controller sử dụng
        Schema::dropIfExists('report_templates');
        
        // Bảng review_reports: không có model, không có controller, không có dữ liệu
        Schema::dropIfExists('review_reports');
        
        // Bảng password_resets: Laravel cũ, không còn được sử dụng (Laravel 9+ dùng password_reset_tokens)
        // Chỉ xóa nếu không có dữ liệu quan trọng
        if (DB::table('password_resets')->count() == 0) {
            Schema::dropIfExists('password_resets');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Không cần rollback vì bảng này không được sử dụng
        // Nếu cần rollback, có thể tạo lại migration gốc
    }
}

