<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->text('mo_ta')->nullable()->after('ten_the_loai');
            $table->string('trang_thai')->default('active')->after('mo_ta');
            $table->string('mau_sac', 7)->nullable()->after('trang_thai');
            $table->string('icon', 50)->nullable()->after('mau_sac');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['mo_ta', 'trang_thai', 'mau_sac', 'icon']);
        });
    }
}
