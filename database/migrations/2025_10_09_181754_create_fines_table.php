<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrow_id');
            $table->unsignedBigInteger('reader_id');
            $table->decimal('amount', 10, 2); // Số tiền phạt
            $table->enum('type', ['late_return', 'damaged_book', 'lost_book', 'other']); // Loại phạt
            $table->text('description')->nullable(); // Mô tả lý do phạt
            $table->enum('status', ['pending', 'paid', 'waived', 'cancelled'])->default('pending'); // Trạng thái
            $table->date('due_date'); // Ngày hết hạn thanh toán
            $table->date('paid_date')->nullable(); // Ngày thanh toán
            $table->text('notes')->nullable(); // Ghi chú
            $table->unsignedBigInteger('created_by'); // Người tạo phạt (librarian)
            $table->timestamps();

            $table->foreign('borrow_id')->references('id')->on('borrows')->onDelete('cascade');
            $table->foreign('reader_id')->references('id')->on('readers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fines');
    }
}
