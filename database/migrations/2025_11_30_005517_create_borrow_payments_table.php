<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('borrow_id');         // Thanh toán cho phiếu mượn
            $table->unsignedBigInteger('borrow_item_id')->nullable(); // Thanh toán cho từng cuốn (nếu có)

            $table->decimal('amount', 10, 2);                // Số tiền

            $table->enum('payment_type', [
                'deposit',        // tiền cọc
                'borrow_fee',     // tiền thuê
                'shipping_fee',   // tiền ship
                'damage_fee',     // đền bù hư hỏng
                'refund',         // hoàn tiền
            ]);

            $table->enum('payment_method', [
                'online',         // MOMO / VNPAY / Banking
                'offline',        // tiền mặt khi giao
            ]);

            $table->enum('payment_status', [
                'pending',        // chờ xử lý
                'success',        // thành công
                'failed',         // thất bại
            ])->default('pending');

            $table->string('transaction_code')->nullable(); // mã giao dịch online hoặc ghi chú offline
            $table->text('note')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('borrow_id')->references('id')->on('borrows')->onDelete('cascade');
            $table->foreign('borrow_item_id')->references('id')->on('borrow_items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_payments');
    }
};
