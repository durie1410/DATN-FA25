<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('full_name');
            $table->string('phone');
            $table->unsignedBigInteger('space_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('hold_until')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, seated, no_show, cancelled
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('space_id')->references('id')->on('spaces')->nullOnDelete();
            $table->foreign('table_id')->references('id')->on('tables')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seat_reservations');
    }
}
