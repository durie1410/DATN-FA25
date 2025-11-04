<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Tên template báo cáo
            $table->string('type', 100); // Loại báo cáo (borrows, readers, books, fines, etc.)
            $table->text('description')->nullable(); // Mô tả báo cáo
            $table->json('columns'); // Các cột hiển thị
            $table->json('filters')->nullable(); // Các bộ lọc có sẵn
            $table->json('group_by')->nullable(); // Nhóm theo cột nào
            $table->json('order_by')->nullable(); // Sắp xếp theo cột nào
            $table->boolean('is_active')->default(true); // Template có hoạt động không
            $table->boolean('is_public')->default(false); // Có công khai không
            $table->unsignedBigInteger('created_by'); // Người tạo template
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_templates');
    }
}
