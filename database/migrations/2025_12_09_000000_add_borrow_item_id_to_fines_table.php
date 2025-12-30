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
        Schema::table('fines', function (Blueprint $table) {
            // Kiểm tra xem cột đã tồn tại chưa
            if (!Schema::hasColumn('fines', 'borrow_item_id')) {
                $table->unsignedBigInteger('borrow_item_id')->nullable()->after('reader_id');
                $table->foreign('borrow_item_id')->references('id')->on('borrow_items')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fines', function (Blueprint $table) {
            if (Schema::hasColumn('fines', 'borrow_item_id')) {
                $table->dropForeign(['borrow_item_id']);
                $table->dropColumn('borrow_item_id');
            }
        });
    }
};
