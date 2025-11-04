<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('readers', function (Blueprint $table) {
            // Xoá cột địa chỉ cũ (nếu bạn không cần nữa)
            $table->dropColumn('dia_chi');

            // Thêm các cột địa chỉ chi tiết
            $table->string('so_nha', 255)->after('gioi_tinh');
            $table->string('phuong_xa', 100)->after('so_nha');
            $table->string('quan_huyen', 100)->after('phuong_xa');
            $table->string('tinh_thanh', 100)->after('quan_huyen');
        });
    }

    public function down(): void
    {
        Schema::table('readers', function (Blueprint $table) {
            $table->dropColumn(['so_nha_duong', 'phuong_xa', 'quan_huyen', 'tinh_thanh']);
            $table->text('dia_chi')->nullable();
        });
    }
};

