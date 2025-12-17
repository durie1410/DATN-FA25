<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('ten_tac_gia');
            $table->string('email')->unique();
            $table->string('so_dien_thoai')->nullable();
            $table->text('dia_chi')->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->text('gioi_thieu')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->string('trang_thai')->default('active'); // active, inactive
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
        Schema::dropIfExists('authors');
    }
};