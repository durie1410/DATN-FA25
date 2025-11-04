<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\AdvancedStatisticsSeeder;
use Database\Seeders\AdvancedDataSeeder;

class SeedAdvancedStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:advanced-stats {--fresh : Chạy fresh migration trước}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo dữ liệu mẫu cho hệ thống thống kê nâng cao';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🚀 Bắt đầu tạo dữ liệu thống kê nâng cao...');

        if ($this->option('fresh')) {
            $this->info('🔄 Chạy fresh migration...');
            $this->call('migrate:fresh');
        }

        $this->info('📊 Tạo dữ liệu mở rộng...');
        $this->call('db:seed', ['--class' => AdvancedDataSeeder::class]);

        $this->info('📈 Tạo dữ liệu thống kê nâng cao...');
        $this->call('db:seed', ['--class' => AdvancedStatisticsSeeder::class]);

        $this->info('✅ Hoàn thành! Dữ liệu thống kê nâng cao đã được tạo thành công.');
        $this->info('🌐 Truy cập: /admin/statistics/advanced để xem dashboard');

        return Command::SUCCESS;
    }
}
