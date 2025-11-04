<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nganh');
            $table->string('ma_nganh')->unique();
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');
            $table->text('mo_ta')->nullable();
            $table->string('truong_nganh')->nullable();
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
        Schema::dropIfExists('departments');
    }
}
