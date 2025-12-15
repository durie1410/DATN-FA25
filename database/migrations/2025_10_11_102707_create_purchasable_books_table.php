<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasableBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasable_books', function (Blueprint $table) {
            $table->id();
            $table->string('ten_sach');
            $table->string('tac_gia');
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->decimal('gia', 10, 2);
            $table->string('nha_xuat_ban')->nullable();
            $table->integer('nam_xuat_ban')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('so_trang')->nullable();
            $table->string('ngon_ngu')->default('Tiếng Việt');
            $table->string('dinh_dang')->default('PDF'); // PDF, EPUB, MOBI
            $table->integer('kich_thuoc_file')->nullable(); // KB
            $table->string('trang_thai')->default('active'); // active, inactive
            $table->integer('so_luong_ban')->default(0);
            $table->decimal('danh_gia_trung_binh', 3, 2)->default(0);
            $table->integer('so_luot_xem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchasable_books');
    }
}
