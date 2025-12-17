<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoLuongTonToPurchasableBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchasable_books', function (Blueprint $table) {
            $table->integer('so_luong_ton')->default(999)->after('so_luong_ban')->comment('Số lượng tồn kho');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchasable_books', function (Blueprint $table) {
            $table->dropColumn('so_luong_ton');
        });
    }
}
