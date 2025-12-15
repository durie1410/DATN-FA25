<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->decimal('gia', 15, 2)->default(0)->after('mo_ta');
            $table->decimal('danh_gia_trung_binh', 3, 2)->default(0)->after('gia');
            $table->integer('so_luong_ban')->default(0)->after('danh_gia_trung_binh');
            $table->integer('so_luot_xem')->default(0)->after('so_luong_ban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['gia', 'danh_gia_trung_binh', 'so_luong_ban', 'so_luot_xem']);
        });
    }
};