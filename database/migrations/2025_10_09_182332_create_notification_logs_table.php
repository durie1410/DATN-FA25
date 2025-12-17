<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('type', 100); // Loại thông báo
            $table->string('channel', 50); // Kênh gửi
            $table->string('recipient', 255); // Người nhận (email, phone, user_id)
            $table->string('subject', 255)->nullable(); // Tiêu đề
            $table->text('content'); // Nội dung đã gửi
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
            $table->text('error_message')->nullable(); // Lỗi nếu có
            $table->json('metadata')->nullable(); // Dữ liệu bổ sung
            $table->timestamp('sent_at')->nullable(); // Thời gian gửi
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('notification_templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_logs');
    }
}
