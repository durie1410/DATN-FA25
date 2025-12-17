<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            // Chỉ thêm các field chưa có
            if (!Schema::hasColumn('books', 'dinh_dang')) {
                $table->string('dinh_dang', 50)->default('Sách giấy')->after('gia');
            }
            if (!Schema::hasColumn('books', 'trang_thai')) {
                $table->string('trang_thai', 20)->default('active')->after('dinh_dang');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['dinh_dang', 'trang_thai']);
        });
    }
};