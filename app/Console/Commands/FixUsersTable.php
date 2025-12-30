<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUsersTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-columns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thêm các cột ngay_sinh và gioi_tinh vào bảng users nếu chưa có';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra và thêm các cột vào bảng users...');
        $this->newLine();

        try {
            // Kiểm tra cột ngay_sinh
            $this->info('1. Kiểm tra cột ngay_sinh...');
            $result = DB::select("SHOW COLUMNS FROM `users` WHERE Field = 'ngay_sinh'");
            
            if (empty($result)) {
                $this->warn('   → Cột ngay_sinh chưa tồn tại. Đang thêm...');
                DB::statement("ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `so_cccd`");
                $this->info('   ✓ Đã thêm cột ngay_sinh thành công!');
            } else {
                $this->info('   ✓ Cột ngay_sinh đã tồn tại.');
            }
            $this->newLine();
            
            // Kiểm tra cột gioi_tinh
            $this->info('2. Kiểm tra cột gioi_tinh...');
            $result = DB::select("SHOW COLUMNS FROM `users` WHERE Field = 'gioi_tinh'");
            
            if (empty($result)) {
                $this->warn('   → Cột gioi_tinh chưa tồn tại. Đang thêm...');
                DB::statement("ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `ngay_sinh`");
                $this->info('   ✓ Đã thêm cột gioi_tinh thành công!');
            } else {
                $this->info('   ✓ Cột gioi_tinh đã tồn tại.');
            }
            $this->newLine();
            
            // Kiểm tra lại
            $this->info('3. Kiểm tra lại các cột...');
            $columns = DB::select("SHOW COLUMNS FROM `users` WHERE Field IN ('ngay_sinh', 'gioi_tinh')");
            
            if (count($columns) == 2) {
                $this->info('   ✓ Cả hai cột đã tồn tại!');
                foreach ($columns as $col) {
                    $this->line("      - {$col->Field}: {$col->Type}");
                }
            } else {
                $this->warn('   ⚠ Có vấn đề! Chỉ tìm thấy ' . count($columns) . ' cột.');
            }
            
            $this->newLine();
            $this->info('========================================');
            $this->info('  HOÀN THÀNH!');
            $this->info('========================================');
            $this->newLine();
            $this->info('Bây giờ bạn có thể:');
            $this->line('1. Mở lại trang cập nhật thông tin tài khoản');
            $this->line('2. Điền đầy đủ thông tin và nhấn "Cập nhật"');
            $this->line('3. Lỗi sẽ không còn xuất hiện nữa!');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('LỖI: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            $this->newLine();
            $this->warn('Vui lòng kiểm tra:');
            $this->line('1. Database connection trong file .env');
            $this->line('2. Quyền truy cập database');
            $this->line('3. Tên bảng "users" có tồn tại không');
            
            return Command::FAILURE;
        }
    }
}

