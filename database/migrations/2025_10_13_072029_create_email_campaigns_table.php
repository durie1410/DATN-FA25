<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Tên chiến dịch
            $table->string('subject', 255); // Tiêu đề email
            $table->text('content'); // Nội dung email
            $table->string('template', 100)->default('marketing'); // Template sử dụng
            $table->json('target_criteria')->nullable(); // Tiêu chí đối tượng
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'cancelled'])->default('draft');
            $table->timestamp('scheduled_at')->nullable(); // Thời gian gửi
            $table->timestamp('sent_at')->nullable(); // Thời gian đã gửi
            $table->integer('total_recipients')->default(0); // Tổng số người nhận
            $table->integer('sent_count')->default(0); // Số email đã gửi
            $table->integer('opened_count')->default(0); // Số email đã mở
            $table->integer('clicked_count')->default(0); // Số email đã click
            $table->json('metadata')->nullable(); // Dữ liệu bổ sung
            $table->unsignedBigInteger('created_by'); // Người tạo
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_campaigns');
    }
}
