<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoCccdToReadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('readers', function (Blueprint $table) {
            $table->string('so_cccd', 20)->nullable()->after('so_dien_thoai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('readers', function (Blueprint $table) {
            $table->dropColumn('so_cccd');
        });
    }
}
