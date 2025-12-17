<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIsSelectedToBorrowCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if column doesn't exist before adding
        if (!Schema::hasColumn('borrow_cart_items', 'is_selected')) {
            Schema::table('borrow_cart_items', function (Blueprint $table) {
                $table->boolean('is_selected')->default(true)->after('note');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrow_cart_items', function (Blueprint $table) {
            $table->dropColumn('is_selected');
        });
    }
}
