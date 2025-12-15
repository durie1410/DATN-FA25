<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readers', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten', 255);
            $table->string('email', 255)->unique();
            $table->string('so_dien_thoai', 20);
            $table->date('ngay_sinh');
            $table->enum('gioi_tinh', ['Nam', 'Nu', 'Khac']);
            $table->text('dia_chi');
            $table->string('so_the_doc_gia', 20)->unique();
            $table->date('ngay_cap_the');
            $table->date('ngay_het_han');
            $table->enum('trang_thai', ['Hoat dong', 'Tam khoa', 'Het han'])->default('Hoat dong');
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
        Schema::dropIfExists('readers');
    }
}
