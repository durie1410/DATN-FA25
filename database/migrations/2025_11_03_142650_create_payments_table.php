<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type'); // rental_fee, shipping_fee, late_fee, other
            $table->string('method')->nullable();
            $table->decimal('amount', 12, 2);
            $table->dateTime('paid_at')->nullable();
            $table->string('txn_ref')->nullable();
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
