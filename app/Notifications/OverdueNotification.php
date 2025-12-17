<?php

namespace App\Notifications;

use App\Models\Borrow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $borrow;

    public function __construct(Borrow $borrow)
    {
        $this->borrow = $borrow;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $daysOverdue = now()->diffInDays($this->borrow->ngay_hen_tra);

        return (new MailMessage)
            ->subject('⚠️ CẢNH BÁO: Sách quá hạn trả - Thư Viện Online')
            ->greeting('Xin chào ' . $this->borrow->reader->ho_ten . '!')
            ->line('🚨 QUAN TRỌNG: Bạn có sách đã quá hạn trả!')
            ->line('📖 Sách: ' . $this->borrow->book->ten_sach)
            ->line('📅 Hạn trả: ' . $this->borrow->ngay_hen_tra->format('d/m/Y'))
            ->line('⏰ Quá hạn: ' . $daysOverdue . ' ngày')
            ->line('💰 Phí phạt: ' . ($daysOverdue * 5000) . ' VNĐ')
            ->action('Trả sách ngay', url('/admin/borrows'))
            ->line('Vui lòng trả sách ngay lập tức để tránh phí phạt tăng cao.')
            ->line('Liên hệ thư viện nếu có vấn đề: 0243.3323.6714');
    }

    public function toArray($notifiable)
    {
        $daysOverdue = now()->diffInDays($this->borrow->ngay_hen_tra);
        
        return [
            'type' => 'overdue_warning',
            'borrow_id' => $this->borrow->id,
            'book_title' => $this->borrow->book->ten_sach,
            'due_date' => $this->borrow->ngay_hen_tra->format('d/m/Y'),
            'days_overdue' => $daysOverdue,
            'fine_amount' => $daysOverdue * 5000,
            'message' => 'Sách "' . $this->borrow->book->ten_sach . '" đã quá hạn ' . $daysOverdue . ' ngày. Phí phạt: ' . ($daysOverdue * 5000) . ' VNĐ',
        ];
    }
}
