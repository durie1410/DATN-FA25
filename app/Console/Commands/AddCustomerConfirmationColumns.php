<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCustomerConfirmationColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'borrows:add-confirmation-columns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thêm các cột xác nhận khách hàng vào bảng borrows';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Đang thêm các cột xác nhận khách hàng vào bảng borrows...');
        $this->newLine();

        try {
            // Kiểm tra bảng borrows
            if (!Schema::hasTable('borrows')) {
                $this->error('✗ Bảng borrows không tồn tại!');
                return 1;
            }

            $this->info('✓ Bảng borrows đã tồn tại');

            // Thêm cột customer_confirmed_delivery
            if (Schema::hasColumn('borrows', 'customer_confirmed_delivery')) {
                $this->info('✓ Cột customer_confirmed_delivery đã tồn tại');
            } else {
                $this->info('Đang thêm cột customer_confirmed_delivery...');
                DB::statement("
                    ALTER TABLE borrows 
                    ADD COLUMN customer_confirmed_delivery TINYINT(1) NOT NULL DEFAULT 0 
                    COMMENT 'Khách hàng đã xác nhận nhận sách'
                ");
                $this->info('✓ Đã thêm cột customer_confirmed_delivery thành công!');
            }

            // Thêm cột customer_confirmed_delivery_at
            if (Schema::hasColumn('borrows', 'customer_confirmed_delivery_at')) {
                $this->info('✓ Cột customer_confirmed_delivery_at đã tồn tại');
            } else {
                $this->info('Đang thêm cột customer_confirmed_delivery_at...');
                DB::statement("
                    ALTER TABLE borrows 
                    ADD COLUMN customer_confirmed_delivery_at TIMESTAMP NULL DEFAULT NULL 
                    COMMENT 'Thời gian khách hàng xác nhận nhận sách'
                ");
                $this->info('✓ Đã thêm cột customer_confirmed_delivery_at thành công!');
            }

            $this->newLine();
            $this->info('==========================================');
            $this->info('✓ HOÀN TẤT!');
            $this->info('==========================================');
            $this->newLine();
            $this->info('Các cột đã được thêm thành công vào bảng borrows!');
            $this->info('Bây giờ bạn có thể làm mới trang web và lỗi sẽ biến mất.');

            return 0;

        } catch (\Exception $e) {
            $this->error('✗ Lỗi: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return 1;
        }
    }
}
