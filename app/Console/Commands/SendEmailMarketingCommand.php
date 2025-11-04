<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailCampaign;
use App\Services\EmailMarketingService;
use Carbon\Carbon;

class SendEmailMarketingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-marketing:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi các chiến dịch email marketing đã được lên lịch';

    protected $emailMarketingService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmailMarketingService $emailMarketingService)
    {
        parent::__construct();
        $this->emailMarketingService = $emailMarketingService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Bắt đầu kiểm tra chiến dịch email marketing...');

        // Lấy các chiến dịch đã được lên lịch và đến giờ gửi
        $campaigns = EmailCampaign::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('Không có chiến dịch nào cần gửi.');
            return 0;
        }

        $this->info("Tìm thấy {$campaigns->count()} chiến dịch cần gửi.");

        $totalSent = 0;
        foreach ($campaigns as $campaign) {
            try {
                $this->info("Đang gửi chiến dịch: {$campaign->name}");
                
                $sentCount = $this->emailMarketingService->sendCampaign($campaign);
                $totalSent += $sentCount;
                
                $this->info("✓ Đã gửi {$sentCount} email cho chiến dịch '{$campaign->name}'");
                
            } catch (\Exception $e) {
                $this->error("✗ Lỗi khi gửi chiến dịch '{$campaign->name}': " . $e->getMessage());
            }
        }

        $this->info("Hoàn thành! Đã gửi tổng cộng {$totalSent} email.");
        return 0;
    }
}
