<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrow_cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrow_cart_id');
            $table->unsignedBigInteger('book_id');
            $table->integer('quantity')->default(1);
            $table->integer('borrow_days')->default(14);
            $table->decimal('distance', 8, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('borrow_cart_id')->references('id')->on('borrow_carts')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->index('borrow_cart_id');
            $table->index('book_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrow_cart_items');
    }
}
