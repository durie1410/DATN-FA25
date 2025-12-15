<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            // Xóa các cột cũ không còn cần
            $columnsToDrop = [
                'book_id', 'ngay_hen_tra', 'ngay_tra_thuc_te', 
                'so_lan_gia_han', 'ngay_gia_han_cuoi'
            ];
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('borrows', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('borrows', function (Blueprint $table) {
            // Thêm các cột mới, chỉ thêm nếu chưa tồn tại
            if (!Schema::hasColumn('borrows', 'ten_nguoi_muon')) {
                $table->string('ten_nguoi_muon')->nullable()->after('id');
            }
            if (!Schema::hasColumn('borrows', 'tinh_thanh')) {
                $table->string('tinh_thanh')->nullable()->after('ten_nguoi_muon');
            }
            if (!Schema::hasColumn('borrows', 'huyen')) {
                $table->string('huyen')->nullable()->after('tinh_thanh');
            }
            if (!Schema::hasColumn('borrows', 'xa')) {
                $table->string('xa')->nullable()->after('huyen');
            }
            if (!Schema::hasColumn('borrows', 'so_nha')) {
                $table->string('so_nha')->nullable()->after('xa');
            }
            if (!Schema::hasColumn('borrows', 'so_dien_thoai')) {
                $table->string('so_dien_thoai')->nullable()->after('so_nha');
            }
            if (!Schema::hasColumn('borrows', 'ngay_muon')) {
                $table->date('ngay_muon')->after('librarian_id');
            }
            if (!Schema::hasColumn('borrows', 'ghi_chu')) {
                $table->text('ghi_chu')->nullable()->after('ngay_muon');
            }
            if (!Schema::hasColumn('borrows', 'tien_coc')) {
                $table->decimal('tien_coc', 10, 2)->default(0.00)->after('ghi_chu');
            }
            if (!Schema::hasColumn('borrows', 'tien_ship')) {
                $table->decimal('tien_ship', 10, 2)->default(0.00)->after('tien_coc');
            }
            if (!Schema::hasColumn('borrows', 'tien_thue')) {
                $table->decimal('tien_thue', 10, 2)->default(0.00)->after('tien_ship');
            }
            if (!Schema::hasColumn('borrows', 'voucher_id')) {
                $table->unsignedBigInteger('voucher_id')->nullable()->after('tien_thue');
            }
            if (!Schema::hasColumn('borrows', 'tong_tien')) {
                $table->decimal('tong_tien', 10, 2)->default(0.00)->after('voucher_id');
            }
            if (!Schema::hasColumn('borrows', 'trang_thai')) {
                $table->enum('trang_thai', [
                    'chua_nhan', 'chua_hoan_tat', 'Dang muon', 
                    'Da tra', 'Qua han', 'Mat sach', 'Thanh ly'
                ])->default('Dang muon')->after('tong_tien');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            // Xóa các cột mới
            $columnsToDrop = [
                'ten_nguoi_muon', 'tinh_thanh', 'huyen', 'xa', 'so_nha', 
                'so_dien_thoai', 'voucher_id', 'tien_coc', 'tien_ship', 
                'tien_thue', 'tong_tien', 'trang_thai', 'ghi_chu'
            ];
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('borrows', $col)) {
                    $table->dropColumn($col);
                }
            }

            // Thêm lại cột cũ nếu rollback
            if (!Schema::hasColumn('borrows', 'book_id')) {
                $table->unsignedBigInteger('book_id')->nullable()->after('reader_id');
            }
            if (!Schema::hasColumn('borrows', 'ngay_hen_tra')) {
                $table->date('ngay_hen_tra')->nullable()->after('ngay_muon');
            }
            if (!Schema::hasColumn('borrows', 'ngay_tra_thuc_te')) {
                $table->date('ngay_tra_thuc_te')->nullable()->after('ngay_hen_tra');
            }
            if (!Schema::hasColumn('borrows', 'so_lan_gia_han')) {
                $table->integer('so_lan_gia_han')->default(0)->after('ngay_tra_thuc_te');
            }
            if (!Schema::hasColumn('borrows', 'ngay_gia_han_cuoi')) {
                $table->date('ngay_gia_han_cuoi')->nullable()->after('so_lan_gia_han');
            }
        });
    }
};
