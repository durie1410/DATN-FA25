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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('purchasable_book_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2); // Giá tại thời điểm thêm vào giỏ
            $table->decimal('total_price', 15, 2); // quantity * price
            $table->timestamps();
            
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('purchasable_book_id')->references('id')->on('purchasable_books')->onDelete('cascade');
            $table->unique(['cart_id', 'purchasable_book_id']); // Mỗi sách chỉ có 1 item trong giỏ
            $table->index(['cart_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};