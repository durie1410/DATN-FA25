<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->enum('type', ['Nhap kho', 'Xuat kho', 'Chuyen kho', 'Kiem ke', 'Thanh ly', 'Sua chua']); // Loại giao dịch
            $table->integer('quantity')->default(1); // Số lượng
            $table->string('from_location', 100)->nullable(); // Từ vị trí
            $table->string('to_location', 100)->nullable(); // Đến vị trí
            $table->enum('condition_before', ['Moi', 'Tot', 'Trung binh', 'Cu', 'Hong'])->nullable(); // Tình trạng trước
            $table->enum('condition_after', ['Moi', 'Tot', 'Trung binh', 'Cu', 'Hong'])->nullable(); // Tình trạng sau
            $table->enum('status_before', ['Co san', 'Dang muon', 'Mat', 'Hong', 'Thanh ly'])->nullable(); // Trạng thái trước
            $table->enum('status_after', ['Co san', 'Dang muon', 'Mat', 'Hong', 'Thanh ly'])->nullable(); // Trạng thái sau
            $table->text('reason')->nullable(); // Lý do
            $table->text('notes')->nullable(); // Ghi chú
            $table->unsignedBigInteger('performed_by'); // Người thực hiện
            $table->timestamps();

            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['inventory_id', 'type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
    }
}
