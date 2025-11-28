<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id(); // id bigint unsigned auto increment
            $table->foreignId('reader_id')->nullable()->constrained('users')->onDelete('set null')->comment('ID người dùng áp dụng'); // nếu reader_id là user
            $table->string('ma')->index()->comment('Mã giảm giá');
            $table->enum('loai', ['percentage', 'fixed'])->comment('Loại giảm giá: phần trăm hoặc cố định');
            $table->decimal('gia_tri', 10, 2)->comment('Giá trị giảm');
            $table->integer('so_luong')->default(0)->comment('Số lượng mã khả dụng');
            $table->text('mo_ta')->nullable()->comment('Mô tả chi tiết mã giảm');
            $table->decimal('don_toi_thieu', 10, 2)->default(0.00)->comment('Giá trị đơn hàng tối thiểu để áp dụng');
            $table->date('ngay_bat_dau')->nullable()->comment('Ngày bắt đầu hiệu lực');
            $table->date('ngay_ket_thuc')->nullable()->comment('Ngày hết hạn');
            $table->tinyInteger('kich_hoat')->default(1)->comment('1: hoạt động, 0: ngưng');
            $table->enum('trang_thai', ['active', 'inactive', 'expired'])->default('active')->comment('Trạng thái mã');

            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
