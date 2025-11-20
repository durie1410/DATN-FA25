<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // khách không đăng nhập = null
            $table->enum('role', ['user', 'assistant']);
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('ai_chat_messages');
}
}