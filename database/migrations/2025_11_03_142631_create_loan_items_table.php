<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('book_item_id');
            $table->decimal('rental_fee', 12, 2)->default(0);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->dateTime('returned_at')->nullable();
            $table->string('condition_on_return')->nullable();
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->decimal('lost_fee', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('book_item_id')->references('id')->on('book_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_items');
    }
}
