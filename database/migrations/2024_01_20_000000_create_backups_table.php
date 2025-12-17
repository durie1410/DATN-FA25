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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->enum('type', ['manual', 'automatic', 'scheduled', 'full', 'incremental']);
            $table->bigInteger('size')->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('last_restored_at')->nullable();
            $table->unsignedBigInteger('restored_by')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'status']);
            $table->index('created_at');
            $table->index('created_by');
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('restored_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
