<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('reader_id');
            $table->unsignedBigInteger('user_id'); // Người đặt trước
            $table->enum('status', ['pending', 'confirmed', 'ready', 'cancelled', 'expired'])->default('pending');
            $table->integer('priority')->default(1); // Độ ưu tiên trong hàng đợi
            $table->date('reservation_date'); // Ngày đặt trước
            $table->date('expiry_date'); // Ngày hết hạn đặt trước
            $table->date('ready_date')->nullable(); // Ngày sách sẵn sàng
            $table->date('pickup_date')->nullable(); // Ngày nhận sách
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('reader_id')->references('id')->on('readers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Mỗi user chỉ có thể đặt trước 1 lần/sách
            $table->unique(['book_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
