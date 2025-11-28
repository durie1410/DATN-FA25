<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;

class ClearReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:clear {--force : Xóa mà không cần xác nhận}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa tất cả dữ liệu đặt trước (Quản lý đặt trước)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = Reservation::count();
        
        if ($count === 0) {
            $this->info('✅ Không có dữ liệu đặt trước nào để xóa.');
            return 0;
        }

        if (!$this->option('force')) {
            if (!$this->confirm("⚠️  Bạn có chắc chắn muốn xóa tất cả {$count} bản ghi đặt trước? Điều này không thể hoàn tác!")) {
                $this->info('❌ Đã hủy thao tác.');
                return 0;
            }
        }

        try {
            // Xóa tất cả dữ liệu đặt trước
            Reservation::query()->delete();
            
            $this->info("✅ Đã xóa thành công {$count} bản ghi đặt trước!");
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Lỗi khi xóa dữ liệu: ' . $e->getMessage());
            return 1;
        }
    }
}
