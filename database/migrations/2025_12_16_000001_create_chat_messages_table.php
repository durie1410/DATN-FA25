<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('support_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_type', 20); // guest, user, support
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};

