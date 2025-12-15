<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCartsAndCartItemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Xóa bảng cart_items trước (có foreign key đến carts)
        Schema::dropIfExists('cart_items');
        
        // Sau đó xóa bảng carts
        Schema::dropIfExists('carts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Không cần khôi phục lại vì đã xóa giỏ hàng cũ
    }
}
