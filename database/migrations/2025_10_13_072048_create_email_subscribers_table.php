<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->unique(); // Email đăng ký
            $table->string('name', 255)->nullable(); // Tên người đăng ký
            $table->enum('status', ['active', 'unsubscribed', 'bounced'])->default('active');
            $table->json('preferences')->nullable(); // Tùy chọn nhận email
            $table->json('tags')->nullable(); // Tags phân loại
            $table->timestamp('subscribed_at'); // Thời gian đăng ký
            $table->timestamp('unsubscribed_at')->nullable(); // Thời gian hủy đăng ký
            $table->string('source', 100)->nullable(); // Nguồn đăng ký
            $table->unsignedBigInteger('user_id')->nullable(); // Liên kết với user
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'subscribed_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_subscribers');
    }
}
