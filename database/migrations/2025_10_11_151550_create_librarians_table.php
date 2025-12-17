<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('librarians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ho_ten');
            $table->string('ma_thu_thu')->unique();
            $table->string('email')->nullable();
            $table->string('so_dien_thoai')->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['male', 'female', 'other'])->nullable();
            $table->text('dia_chi')->nullable();
            $table->string('chuc_vu')->nullable();
            $table->string('phong_ban')->nullable();
            $table->date('ngay_vao_lam')->nullable();
            $table->date('ngay_het_han_hop_dong')->nullable();
            $table->decimal('luong_co_ban', 10, 2)->nullable();
            $table->enum('trang_thai', ['active', 'inactive'])->default('active');
            $table->string('anh_dai_dien')->nullable();
            $table->text('bang_cap')->nullable();
            $table->text('kinh_nghiem')->nullable();
            $table->text('ghi_chu')->nullable();
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
        Schema::dropIfExists('librarians');
    }
}
