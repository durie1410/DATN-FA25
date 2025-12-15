<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 50)->unique(); // Số phiếu nhập
            $table->date('receipt_date'); // Ngày nhập
            $table->unsignedBigInteger('book_id'); // Loại sách
            $table->integer('quantity'); // Số lượng nhập
            $table->string('storage_location', 100); // Vị trí lưu trữ (kệ, tầng, vị trí)
            $table->enum('storage_type', ['Kho', 'Trung bay'])->default('Kho'); // Loại lưu trữ
            $table->decimal('unit_price', 10, 2)->nullable(); // Giá mua đơn vị
            $table->decimal('total_price', 10, 2)->nullable(); // Tổng giá
            $table->string('supplier', 255)->nullable(); // Nhà cung cấp
            $table->unsignedBigInteger('received_by'); // Người nhập
            $table->unsignedBigInteger('approved_by')->nullable(); // Người phê duyệt
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['receipt_date', 'status']);
            $table->index('receipt_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_receipts');
    }
}
