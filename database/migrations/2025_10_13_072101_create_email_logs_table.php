<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id')->nullable(); // Chiến dịch
            $table->string('email', 255); // Email người nhận
            $table->string('subject', 255); // Tiêu đề
            $table->enum('status', ['sent', 'delivered', 'opened', 'clicked', 'bounced', 'failed'])->default('sent');
            $table->timestamp('sent_at'); // Thời gian gửi
            $table->timestamp('delivered_at')->nullable(); // Thời gian giao
            $table->timestamp('opened_at')->nullable(); // Thời gian mở
            $table->timestamp('clicked_at')->nullable(); // Thời gian click
            $table->string('error_message', 500)->nullable(); // Lỗi nếu có
            $table->json('metadata')->nullable(); // Dữ liệu bổ sung
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('email_campaigns')->onDelete('cascade');
            $table->index(['email', 'sent_at']);
            $table->index(['campaign_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
}
