<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Sử dụng DB raw để tránh lỗi với after() clause
        if (!Schema::hasColumn('borrows', 'customer_rejected_delivery')) {
            DB::statement("ALTER TABLE borrows ADD COLUMN customer_rejected_delivery TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Khách hàng đã từ chối nhận sách'");
        }
        
        if (!Schema::hasColumn('borrows', 'customer_rejected_delivery_at')) {
            DB::statement("ALTER TABLE borrows ADD COLUMN customer_rejected_delivery_at TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian khách hàng từ chối nhận sách'");
        }
        
        if (!Schema::hasColumn('borrows', 'customer_rejection_reason')) {
            DB::statement("ALTER TABLE borrows ADD COLUMN customer_rejection_reason TEXT NULL DEFAULT NULL COMMENT 'Lý do khách hàng từ chối nhận sách'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrows', function (Blueprint $table) {
            if (Schema::hasColumn('borrows', 'customer_rejected_delivery')) {
                $table->dropColumn('customer_rejected_delivery');
            }
            
            if (Schema::hasColumn('borrows', 'customer_rejected_delivery_at')) {
                $table->dropColumn('customer_rejected_delivery_at');
            }
            
            if (Schema::hasColumn('borrows', 'customer_rejection_reason')) {
                $table->dropColumn('customer_rejection_reason');
            }
        });
    }
};
