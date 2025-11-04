<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Tên template
            $table->string('type', 100); // Loại thông báo (borrow_reminder, overdue, reservation_ready, etc.)
            $table->string('channel', 50); // Kênh gửi (email, sms, push, database)
            $table->string('subject', 255)->nullable(); // Tiêu đề (cho email)
            $table->text('content'); // Nội dung template
            $table->json('variables')->nullable(); // Các biến có thể thay thế
            $table->boolean('is_active')->default(true); // Template có hoạt động không
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
        Schema::dropIfExists('notification_templates');
    }
}
