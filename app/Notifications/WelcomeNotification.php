<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Chào mừng bạn đến với Thư viện!')
                    ->greeting('Xin chào ' . $this->user->name . '!')
                    ->line('Chúc mừng bạn đã đăng ký thành công tài khoản tại hệ thống quản lý thư viện.')
                    ->line('Với tài khoản này, bạn có thể:')
                    ->line('• Tìm kiếm và mượn sách')
                    ->line('• Đặt chỗ sách trước')
                    ->line('• Xem lịch sử mượn sách')
                    ->line('• Đánh giá và bình luận về sách')
                    ->line('• Quản lý danh sách yêu thích')
                    ->action('Truy cập thư viện', url('/'))
                    ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!')
                    ->salutation('Trân trọng,')
                    ->line('Đội ngũ Thư viện');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
