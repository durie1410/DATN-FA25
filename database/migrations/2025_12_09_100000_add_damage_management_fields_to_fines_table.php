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
            // Thông tin hư hỏng chi tiết
            $table->text('damage_description')->nullable()->after('description')->comment('Mô tả chi tiết tình trạng hư hỏng');
            $table->json('damage_images')->nullable()->after('damage_description')->comment('Mảng đường dẫn ảnh minh chứng hư hỏng');
            $table->enum('damage_severity', ['nhe', 'trung_binh', 'nang', 'mat_sach'])->nullable()->after('damage_images')->comment('Mức độ hư hỏng');
            $table->string('damage_type')->nullable()->after('damage_severity')->comment('Loại hư hỏng: trang_bi_rach, bia_bi_hu, trang_bi_meo, mat_trang, etc.');
            
            // Tình trạng sách trước và sau
            $table->string('condition_before')->nullable()->after('damage_type')->comment('Tình trạng sách trước khi mượn');
            $table->string('condition_after')->nullable()->after('condition_before')->comment('Tình trạng sách sau khi trả');
            
            // Thông tin kiểm tra
            $table->unsignedBigInteger('inspected_by')->nullable()->after('condition_after')->comment('Người kiểm tra hư hỏng');
            $table->timestamp('inspected_at')->nullable()->after('inspected_by')->comment('Thời gian kiểm tra');
            $table->text('inspection_notes')->nullable()->after('inspected_at')->comment('Ghi chú kiểm tra');
        });
        
        // Thêm foreign key cho inspected_by
        Schema::table('fines', function (Blueprint $table) {
            $table->foreign('inspected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fines', function (Blueprint $table) {
            $table->dropForeign(['inspected_by']);
            $table->dropColumn([
                'damage_description',
                'damage_images',
                'damage_severity',
                'damage_type',
                'condition_before',
                'condition_after',
                'inspected_by',
                'inspected_at',
                'inspection_notes'
            ]);
        });
    }
};
