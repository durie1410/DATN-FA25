<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisplayAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('display_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('inventory_id')->nullable(); // Null nếu chỉ track theo book_id
            $table->integer('quantity_on_display')->default(0); // Số lượng đang trưng bày
            $table->integer('quantity_in_stock')->default(0); // Số lượng còn trong kho
            $table->string('display_area', 100)->nullable(); // Khu vực trưng bày (ví dụ: "Kệ A1", "Gian trung tâm")
            $table->date('display_start_date')->nullable(); // Ngày bắt đầu trưng bày
            $table->date('display_end_date')->nullable(); // Ngày kết thúc trưng bày
            $table->unsignedBigInteger('allocated_by'); // Người phân bổ
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('set null');
            $table->foreign('allocated_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['book_id', 'display_start_date']);
            $table->index('display_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('display_allocations');
    }
}
