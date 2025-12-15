<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('ten_khoa');
            $table->string('ma_khoa')->unique();
            $table->text('mo_ta')->nullable();
            $table->string('truong_khoa')->nullable();
            $table->string('so_dien_thoai')->nullable();
            $table->string('email')->nullable();
            $table->text('dia_chi')->nullable();
            $table->string('website')->nullable();
            $table->date('ngay_thanh_lap')->nullable();
            $table->enum('trang_thai', ['active', 'inactive'])->default('active');
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('faculties');
    }
}
