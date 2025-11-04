<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nap_tien', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reader_id');
            $table->decimal('so_tien', 15, 2);
            $table->enum('trang_thai', ['cho_xac_nhan', 'thanh_cong', 'that_bai'])->default('cho_xac_nhan');
            $table->string('ma_giao_dich')->unique();
            $table->timestamps();

            $table->foreign('reader_id')->references('id')->on('readers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nap_tien');
    }
};

