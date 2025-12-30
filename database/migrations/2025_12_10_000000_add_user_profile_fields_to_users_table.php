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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'ngay_sinh')) {
                $table->date('ngay_sinh')->nullable()->after('so_cccd');
            }
            if (!Schema::hasColumn('users', 'gioi_tinh')) {
                $table->enum('gioi_tinh', ['Nam', 'Nu', 'Khac'])->nullable()->after('ngay_sinh');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ngay_sinh')) {
                $table->dropColumn('ngay_sinh');
            }
            if (Schema::hasColumn('users', 'gioi_tinh')) {
                $table->dropColumn('gioi_tinh');
            }
        });
    }
};
