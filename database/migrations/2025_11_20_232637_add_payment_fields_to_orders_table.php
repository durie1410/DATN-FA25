<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thêm trường transaction_id để lưu mã giao dịch từ payment gateway
            $table->string('transaction_id')->nullable()->after('payment_method');

            // Thêm trường paid_at để lưu thời gian thanh toán
            $table->timestamp('paid_at')->nullable()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'paid_at']);
        });
    }
}
