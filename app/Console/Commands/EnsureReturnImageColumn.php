<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnsureReturnImageColumn extends Command
{
    protected $signature = 'borrows:ensure-return-image-column';
    protected $description = 'Đảm bảo cột anh_hoan_tra tồn tại trong bảng borrows';

    public function handle()
    {
        $this->info('🔍 Đang kiểm tra cột anh_hoan_tra...');
        
        try {
            // Kiểm tra bằng Schema
            if (Schema::hasColumn('borrows', 'anh_hoan_tra')) {
                $this->info('✅ Cột anh_hoan_tra đã tồn tại!');
                return Command::SUCCESS;
            }
            
            // Kiểm tra bằng SQL
            $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
            
            if (!empty($columns)) {
                $this->info('✅ Cột anh_hoan_tra đã tồn tại!');
                return Command::SUCCESS;
            }
            
            $this->warn('⚠️  Cột chưa tồn tại. Đang thêm cột...');
            
            // Kiểm tra cột tinh_trang_sach
            $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
            
            if (!empty($checkTinhTrang)) {
                // FIX: COMMENT phải đứng trước AFTER trong MySQL
                DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng' AFTER `tinh_trang_sach`");
                $this->info('✅ Đã thêm cột anh_hoan_tra sau cột tinh_trang_sach');
            } else {
                DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
                $this->info('✅ Đã thêm cột anh_hoan_tra vào cuối bảng');
            }
            
            // Kiểm tra lại
            $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
            if (!empty($columns)) {
                $col = $columns[0];
                $this->table(['Field', 'Type', 'Null', 'Default'], [
                    [
                        $col->Field,
                        $col->Type,
                        $col->Null,
                        $col->Default ?? 'NULL'
                    ]
                ]);
                $this->info('🎉 Hoàn tất! Cột đã được thêm thành công.');
                return Command::SUCCESS;
            } else {
                $this->error('❌ Không thể thêm cột. Vui lòng kiểm tra quyền database.');
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Lỗi: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
            return Command::FAILURE;
        }
    }
}
