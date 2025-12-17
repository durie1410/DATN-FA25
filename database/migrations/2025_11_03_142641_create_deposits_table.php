<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount_required', 12, 2);
            $table->decimal('amount_held', 12, 2)->default(0);
            $table->string('hold_method')->nullable(); // cash, transfer, wallet
            $table->decimal('released_amount', 12, 2)->default(0);
            $table->dateTime('released_at')->nullable();
            $table->string('status')->default('held'); // held, partially_released, forfeited
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
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
        Schema::dropIfExists('deposits');
    }
}
