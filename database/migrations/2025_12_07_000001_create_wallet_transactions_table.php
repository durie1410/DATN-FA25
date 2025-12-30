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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->string('type')->comment('Loại giao dịch: deposit (nạp), refund (hoàn cọc), withdraw (rút), payment (thanh toán)');
            $table->decimal('amount', 15, 2)->comment('Số tiền');
            $table->decimal('balance_before', 15, 2)->comment('Số dư trước giao dịch');
            $table->decimal('balance_after', 15, 2)->comment('Số dư sau giao dịch');
            $table->string('description')->nullable()->comment('Mô tả giao dịch');
            $table->string('reference_type')->nullable()->comment('Loại tham chiếu: App\Models\Borrow, App\Models\Order, etc.');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID của đối tượng tham chiếu');
            $table->string('status')->default('completed')->comment('Trạng thái: pending, completed, failed, cancelled');
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->index(['wallet_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
};



