<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->string('barcode', 100)->unique(); // Mã vạch sách
            $table->string('location', 100); // Vị trí trong kho (kệ, tầng, vị trí)
            $table->enum('condition', ['Moi', 'Tot', 'Trung binh', 'Cu', 'Hong'])->default('Moi'); // Tình trạng sách
            $table->enum('status', ['Co san', 'Dang muon', 'Mat', 'Hong', 'Thanh ly'])->default('Co san'); // Trạng thái
            $table->decimal('purchase_price', 10, 2)->nullable(); // Giá mua
            $table->date('purchase_date')->nullable(); // Ngày mua
            $table->text('notes')->nullable(); // Ghi chú
            $table->unsignedBigInteger('created_by'); // Người tạo
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['book_id', 'status']);
            $table->index('barcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
