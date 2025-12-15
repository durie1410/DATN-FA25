<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('borrow_id');      // Phiếu mượn chung
            $table->unsignedBigInteger('borrow_item_id'); // Giao từng cuốn

            $table->string('shipper_name')->nullable();
            $table->string('shipper_phone')->nullable();

            $table->enum('status', [
                'dang_giao',      // đang vận chuyển
                'da_giao',        // giao thành công
                'khong_nhan',     // khách không nhận
                'hoan_hang',      // trả hàng về
            ])->default('dang_giao');

            $table->text('receiver_note')->nullable();      // ghi chú từ khách khi nhận
            $table->text('shipper_note')->nullable();       // giao hàng ghi chú
            $table->string('proof_image')->nullable();      // ảnh giao hàng

            $table->timestamp('delivered_at')->nullable();  // thời gian giao thành công

            $table->timestamps();

            // FK
            $table->foreign('borrow_id')->references('id')->on('borrows')->onDelete('cascade');
            $table->foreign('borrow_item_id')->references('id')->on('borrow_items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_logs');
    }
};
