<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddReturnImageColumn extends Command
{
    protected $signature = 'db:add-return-image-column';
    protected $description = 'Thêm cột anh_hoan_tra vào bảng borrows';

    public function handle()
    {
        try {
            $this->info('🔍 Đang kiểm tra cột anh_hoan_tra...');
            
            // Kiểm tra xem cột đã tồn tại chưa
            $columns = DB::select("SHOW COLUMNS FROM `borrows` LIKE 'anh_hoan_tra'");
            
            if (empty($columns)) {
                $this->info('➕ Cột chưa tồn tại. Đang thêm cột...');
                
                // FIX: COMMENT phải đứng trước AFTER trong MySQL
                DB::statement("ALTER TABLE `borrows` 
                    ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL 
                    COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'
                    AFTER `tinh_trang_sach`");
                
                $this->info('✅ Đã thêm cột anh_hoan_tra thành công!');
            } else {
                $this->info('ℹ️  Cột anh_hoan_tra đã tồn tại.');
            }
            
            // Kiểm tra lại
            $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
            if (!empty($columns)) {
                $this->table(['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'], [
                    [
                        $columns[0]->Field,
                        $columns[0]->Type,
                        $columns[0]->Null,
                        $columns[0]->Key,
                        $columns[0]->Default ?? 'NULL',
                        $columns[0]->Extra
                    ]
                ]);
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Lỗi: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
