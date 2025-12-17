<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Tự động tạo phạt cho sách trả muộn hàng ngày lúc 8:00
        $schedule->command('fines:create-late-returns')
                 ->dailyAt('08:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Tự động tạo sao lưu hàng ngày lúc 2:00
        $schedule->command('backup:create --type=automatic --description="Daily automatic backup"')
                 ->dailyAt('02:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Dọn dẹp sao lưu cũ hàng tuần (giữ lại 30 ngày)
        $schedule->command('backup:cleanup --days=30')
                 ->weekly()
                 ->sundays()
                 ->at('03:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Housekeeping: mark overdue loans hourly (and future no-show cleanup)
        $schedule->command('library:housekeeping')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Reminders: send due-soon and overdue emails daily at 09:00
        $schedule->command('library:reminders')
                 ->dailyAt('09:00')
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
