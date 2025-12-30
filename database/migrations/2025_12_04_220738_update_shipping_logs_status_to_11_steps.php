<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateShippingLogsStatusTo11Steps extends Migration
{
    /**
     * Run the migrations.
     * Cập nhật cột status trong shipping_logs sang 11 trạng thái mới
     *
     * @return void
     */
    public function up()
    {
        // Map dữ liệu cũ sang dữ liệu mới
        $mapping = [
            'cho_xu_ly' => 'don_hang_moi',
            'dang_chuan_bi' => 'dang_chuan_bi_sach',
            'dang_giao' => 'dang_giao_hang',
            'da_giao_thanh_cong' => 'giao_hang_thanh_cong',
            'giao_that_bai' => 'giao_hang_that_bai',
            'tra_lai_sach' => 'cho_tra_sach',
            'dang_gui_lai' => 'dang_van_chuyen_tra_ve',
            'da_nhan_hang' => 'da_nhan_va_kiem_tra',
            'dang_kiem_tra' => 'da_nhan_va_kiem_tra',
            'thanh_toan_coc' => 'da_nhan_va_kiem_tra',
            'hoan_thanh' => 'hoan_tat_don_hang',
        ];

        // Chuyển sang VARCHAR để update
        DB::statement("ALTER TABLE shipping_logs MODIFY COLUMN status VARCHAR(50) DEFAULT 'don_hang_moi'");

        // Update dữ liệu
        foreach ($mapping as $oldValue => $newValue) {
            DB::table('shipping_logs')
                ->where('status', $oldValue)
                ->update(['status' => $newValue]);
        }

        // Chuyển lại sang ENUM với 11 trạng thái mới
        DB::statement("
            ALTER TABLE shipping_logs 
            MODIFY COLUMN status 
            ENUM(
                'don_hang_moi',
                'dang_chuan_bi_sach',
                'cho_ban_giao_van_chuyen',
                'dang_giao_hang',
                'giao_hang_thanh_cong',
                'giao_hang_that_bai',
                'da_muon_dang_luu_hanh',
                'cho_tra_sach',
                'dang_van_chuyen_tra_ve',
                'da_nhan_va_kiem_tra',
                'hoan_tat_don_hang'
            ) 
            DEFAULT 'don_hang_moi' NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Khôi phục lại 11 trạng thái cũ
        DB::statement("
            ALTER TABLE shipping_logs 
            MODIFY COLUMN status 
            ENUM(
                'cho_xu_ly',
                'dang_chuan_bi',
                'dang_giao',
                'da_giao_thanh_cong',
                'giao_that_bai',
                'tra_lai_sach',
                'dang_gui_lai',
                'da_nhan_hang',
                'dang_kiem_tra',
                'thanh_toan_coc',
                'hoan_thanh'
            ) 
            DEFAULT 'cho_xu_ly' NOT NULL
        ");
    }
}
