<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNgayTraThucTeToBorrowItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrow_items', function (Blueprint $table) {
            if (!Schema::hasColumn('borrow_items', 'ngay_tra_thuc_te')) {
                $table->date('ngay_tra_thuc_te')->nullable()->after('ngay_hen_tra');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrow_items', function (Blueprint $table) {
            if (Schema::hasColumn('borrow_items', 'ngay_tra_thuc_te')) {
                $table->dropColumn('ngay_tra_thuc_te');
            }
        });
    }
}
