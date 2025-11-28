<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->enum('loai_sach', ['binh_thuong', 'quy', 'tham_khao'])
                  ->default('binh_thuong')
                  ->after('trang_thai'); // Thêm cột sau cột 'trang_thai', thay đổi nếu cần
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('loai_sach');
        });
    }
};
