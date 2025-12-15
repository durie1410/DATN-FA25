<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class SendNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send {--type=all : Type of notifications to send (all, overdue, due-tomorrow, reservation-ready, reservation-expiring, fines, card-expiring)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automated notifications to users';

    protected $notificationService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->option('type');
        
        $this->info("Sending {$type} notifications...");

        $totalSent = 0;

        switch ($type) {
            case 'all':
                $totalSent += $this->sendOverdueNotifications();
                $totalSent += $this->sendDueTomorrowNotifications();
                $totalSent += $this->sendReservationReadyNotifications();
                $totalSent += $this->sendReservationExpiryNotifications();
                $totalSent += $this->sendFineNotifications();
                $totalSent += $this->sendReaderCardExpiryNotifications();
                break;
                
            case 'overdue':
                $totalSent += $this->sendOverdueNotifications();
                break;
                
            case 'due-tomorrow':
                $totalSent += $this->sendDueTomorrowNotifications();
                break;
                
            case 'reservation-ready':
                $totalSent += $this->sendReservationReadyNotifications();
                break;
                
            case 'reservation-expiring':
                $totalSent += $this->sendReservationExpiryNotifications();
                break;
                
            case 'fines':
                $totalSent += $this->sendFineNotifications();
                break;
                
            case 'card-expiring':
                $totalSent += $this->sendReaderCardExpiryNotifications();
                break;
                
            default:
                $this->error("Invalid notification type: {$type}");
                return 1;
        }

        $this->info("✅ Sent {$totalSent} notifications successfully!");
        
        return 0;
    }

    protected function sendOverdueNotifications()
    {
        $this->info("📚 Sending overdue book notifications...");
        $count = $this->notificationService->sendOverdueNotifications();
        $this->info("   Sent {$count} overdue notifications");
        return $count;
    }

    protected function sendDueTomorrowNotifications()
    {
        $this->info("📅 Sending due tomorrow notifications...");
        $count = $this->notificationService->sendUpcomingDueNotifications();
        $this->info("   Sent {$count} due tomorrow notifications");
        return $count;
    }

    protected function sendReservationReadyNotifications()
    {
        $this->info("📖 Sending reservation ready notifications...");
        $count = $this->notificationService->sendReservationReadyNotifications();
        $this->info("   Sent {$count} reservation ready notifications");
        return $count;
    }

    protected function sendReservationExpiryNotifications()
    {
        $this->info("⏰ Sending reservation expiry notifications...");
        $count = $this->notificationService->sendReservationExpiryNotifications();
        $this->info("   Sent {$count} reservation expiry notifications");
        return $count;
    }

    protected function sendFineNotifications()
    {
        $this->info("💰 Sending fine notifications...");
        $count = $this->notificationService->sendFineNotifications();
        $this->info("   Sent {$count} fine notifications");
        return $count;
    }

    protected function sendReaderCardExpiryNotifications()
    {
        $this->info("🆔 Sending reader card expiry notifications...");
        $count = $this->notificationService->sendReaderCardExpiryNotifications();
        $this->info("   Sent {$count} reader card expiry notifications");
        return $count;
    }
}
