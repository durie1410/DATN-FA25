<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra và thêm cột ngay_sinh
        $columns = DB::select("SHOW COLUMNS FROM `users` LIKE 'ngay_sinh'");
        if (empty($columns)) {
            DB::statement("ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `so_cccd`");
        }
        
        // Kiểm tra và thêm cột gioi_tinh
        $columns = DB::select("SHOW COLUMNS FROM `users` LIKE 'gioi_tinh'");
        if (empty($columns)) {
            DB::statement("ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `ngay_sinh`");
        }
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

