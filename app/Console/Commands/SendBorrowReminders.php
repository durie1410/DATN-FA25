<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use App\Models\NotificationTemplate;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendBorrowReminders extends Command
{
    protected $signature = 'borrow:send-reminders {--type=all : Type of reminder (all, due-soon, overdue)}';
    protected $description = 'Send automatic reminders for book borrowings';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $type = $this->option('type');
        
        $this->info("Sending {$type} reminders...");

        switch ($type) {
            case 'due-soon':
                $this->sendDueSoonReminders();
                break;
            case 'overdue':
                $this->sendOverdueReminders();
                break;
            case 'all':
            default:
                $this->sendDueSoonReminders();
                $this->sendOverdueReminders();
                break;
        }

        $this->info('Reminders sent successfully!');
    }

    private function sendDueSoonReminders()
    {
        $this->info('Sending due soon reminders...');
        
        // Gửi nhắc nhở 3 ngày trước hạn trả
        $dueSoonBorrows = Borrow::where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', Carbon::now()->addDays(3)->toDateString())
            ->with(['reader', 'book'])
            ->get();

        foreach ($dueSoonBorrows as $borrow) {
            $this->sendReminder($borrow, 'due_soon');
        }

        $this->info("Sent {$dueSoonBorrows->count()} due soon reminders");
    }

    private function sendOverdueReminders()
    {
        $this->info('Sending overdue reminders...');
        
        $overdueBorrows = Borrow::where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', Carbon::now()->toDateString())
            ->with(['reader', 'book'])
            ->get();

        foreach ($overdueBorrows as $borrow) {
            $this->sendReminder($borrow, 'overdue');
        }

        $this->info("Sent {$overdueBorrows->count()} overdue reminders");
    }

    private function sendReminder(Borrow $borrow, $type)
    {
        try {
            $template = NotificationTemplate::where('type', $type)->first();
            
            if (!$template) {
                $this->warn("No template found for type: {$type}");
                return;
            }

            $data = [
                'reader_name' => $borrow->reader->ho_ten,
                'book_title' => $borrow->book->ten_sach,
                'due_date' => $borrow->ngay_hen_tra->format('d/m/Y'),
                'days_overdue' => $borrow->days_overdue,
                'library_name' => config('app.name'),
            ];

            $this->notificationService->sendSimpleEmail(
                $borrow->reader->email,
                $template->subject,
                $template->body,
                $data
            );

            $this->line("Sent {$type} reminder to {$borrow->reader->ho_ten} ({$borrow->reader->email})");

        } catch (\Exception $e) {
            $this->error("Failed to send reminder to {$borrow->reader->ho_ten}: " . $e->getMessage());
        }
    }
}