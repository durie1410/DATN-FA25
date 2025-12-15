<?php

namespace App\Notifications;

use App\Models\Borrow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowReminderNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Nhắc nhở trả sách - Thư Viện Online')
            ->greeting('Xin chào ' . $this->borrow->reader->ho_ten . '!')
            ->line('Bạn có sách đang mượn sắp đến hạn trả:')
            ->line('📖 Sách: ' . $this->borrow->book->ten_sach)
            ->line('📅 Hạn trả: ' . $this->borrow->ngay_hen_tra->format('d/m/Y'))
            ->line('⏰ Còn lại: ' . now()->diffInDays($this->borrow->ngay_hen_tra) . ' ngày')
            ->action('Xem chi tiết', url('/admin/borrows'))
            ->line('Vui lòng trả sách đúng hạn để tránh phí phạt.')
            ->line('Cảm ơn bạn đã sử dụng dịch vụ thư viện!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'borrow_reminder',
            'borrow_id' => $this->borrow->id,
            'book_title' => $this->borrow->book->ten_sach,
            'due_date' => $this->borrow->ngay_hen_tra->format('d/m/Y'),
            'days_left' => now()->diffInDays($this->borrow->ngay_hen_tra),
            'message' => 'Sách "' . $this->borrow->book->ten_sach . '" sắp đến hạn trả vào ' . $this->borrow->ngay_hen_tra->format('d/m/Y'),
        ];
    }
}
