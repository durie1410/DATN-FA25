<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LibraryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;
    protected $content;
    protected $type;
    protected $metadata;

    /**
     * Create a new notification instance.
     */
    public function __construct($subject, $content, $type = 'general', $metadata = [])
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->type = $type;
        $this->metadata = $metadata;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line($this->content)
            ->action('Xem chi tiết', url('/'))
            ->line('Trân trọng, Thư viện');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => $this->subject,
            'content' => $this->content,
            'type' => $this->type,
            'metadata' => $this->metadata,
            'created_at' => now(),
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'subject' => $this->subject,
            'content' => $this->content,
            'type' => $this->type,
            'metadata' => $this->metadata,
        ];
    }
}
