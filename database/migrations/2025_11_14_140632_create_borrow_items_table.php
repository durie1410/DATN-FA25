<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('borrow_id');
            $table->date('ngay_muon')->nullable();
            $table->unsignedBigInteger('book_id');
            $table->decimal('tien_coc', 10, 2)->default(0.00);
            $table->enum('trang_thai_coc', ['cho_xu_ly', 'da_thu', 'da_hoan'])
                  ->default('cho_xu_ly')
                  ->comment('Trạng thái tiền cọc: chờ xử lý, đã thu, đã hoàn');
            $table->decimal('tien_thue', 10, 2)->default(0.00);
            $table->decimal('tien_ship', 10, 2)->default(0.00);
            $table->date('ngay_hen_tra');
            $table->enum('trang_thai', ['Dang muon', 'Da tra', 'Qua han', 'Mat sach'])
                  ->default('Dang muon');
            $table->integer('so_lan_gia_han')->default(0);
            $table->date('ngay_gia_han_cuoi')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Thêm index
            $table->index('borrow_id');
            $table->index('book_id');

            // Nếu muốn ràng buộc khóa ngoại (nếu bảng borrows và books tồn tại)
            // $table->foreign('borrow_id')->references('id')->on('borrows')->onDelete('cascade');
            // $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_items');
    }
};
