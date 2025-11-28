<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInventorieIdToBorrowItemsTable extends Migration
{
    public function up()
    {
        Schema::table('borrow_items', function (Blueprint $table) {
            // Kiểm tra xem cột đã tồn tại chưa
            if (!Schema::hasColumn('borrow_items', 'inventorie_id')) {
                $table->unsignedBigInteger('inventorie_id')->nullable()->after('book_id');
            }
        });
    }

    public function down()
    {
        Schema::table('borrow_items', function (Blueprint $table) {
            $table->dropColumn('inventorie_id');
        });
    }
}
