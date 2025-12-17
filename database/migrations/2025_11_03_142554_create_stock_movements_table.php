<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_item_id')->nullable();
            $table->unsignedBigInteger('book_id')->nullable();
            $table->string('type'); // inbound, outbound, to_display, to_warehouse, adjust
            $table->integer('qty')->default(1);
            $table->unsignedBigInteger('from_location_id')->nullable();
            $table->unsignedBigInteger('to_location_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('book_item_id')->references('id')->on('book_items')->nullOnDelete();
            $table->foreign('book_id')->references('id')->on('books')->nullOnDelete();
            $table->foreign('from_location_id')->references('id')->on('locations')->nullOnDelete();
            $table->foreign('to_location_id')->references('id')->on('locations')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
