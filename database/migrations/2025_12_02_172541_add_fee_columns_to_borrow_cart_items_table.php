<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeColumnsToBorrowCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrow_cart_items', function (Blueprint $table) {
            $table->decimal('tien_coc', 15, 2)->default(0)->after('note')->comment('Tiền cọc đã tính');
            $table->decimal('tien_thue', 15, 2)->default(0)->after('tien_coc')->comment('Tiền thuê đã tính');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrow_cart_items', function (Blueprint $table) {
            $table->dropColumn(['tien_coc', 'tien_thue']);
        });
    }
}
