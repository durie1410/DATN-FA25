<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStorageTypeToInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->enum('storage_type', ['Kho', 'Trung bay'])->default('Kho')->after('location');
            $table->unsignedBigInteger('receipt_id')->nullable()->after('created_by');
            $table->index('storage_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['receipt_id']);
            $table->dropColumn(['storage_type', 'receipt_id']);
        });
    }
}
