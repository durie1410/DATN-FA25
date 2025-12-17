<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('borrower_name');
            $table->string('phone');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('channel')->default('online'); // online, counter
            $table->string('delivery_type')->default('pickup'); // pickup, ship
            $table->string('status')->default('pending');
            $table->decimal('deposit_required', 12, 2)->default(0);
            $table->decimal('rental_fee_estimate', 12, 2)->default(0);
            $table->dateTime('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
