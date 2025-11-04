<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query', 500); // Từ khóa tìm kiếm
            $table->string('type', 50); // Loại tìm kiếm (books, readers, etc.)
            $table->json('filters')->nullable(); // Các bộ lọc đã áp dụng
            $table->integer('results_count')->default(0); // Số kết quả tìm được
            $table->unsignedBigInteger('user_id')->nullable(); // User thực hiện tìm kiếm
            $table->string('ip_address', 45)->nullable(); // IP address
            $table->string('user_agent', 500)->nullable(); // User agent
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['query', 'type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_logs');
    }
}
