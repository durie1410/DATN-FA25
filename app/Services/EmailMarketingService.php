<?php

namespace App\Services;

use App\Models\EmailCampaign;
use App\Models\EmailSubscriber;
use App\Models\EmailLog;
use App\Models\Book;
use App\Models\Reader;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmailMarketingService
{
    /**
     * Tạo chiến dịch email marketing mới
     */
    public function createCampaign($data)
    {
        $campaign = EmailCampaign::create([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'content' => $data['content'],
            'template' => $data['template'] ?? 'marketing',
            'target_criteria' => $data['target_criteria'] ?? [],
            'status' => 'draft',
            'scheduled_at' => isset($data['scheduled_at']) ? Carbon::parse($data['scheduled_at']) : null,
            'created_by' => auth()->id(),
            'metadata' => $data['metadata'] ?? [],
        ]);

        // Tính toán số lượng người nhận
        $recipients = $this->getRecipients($campaign);
        $campaign->update(['total_recipients' => $recipients->count()]);

        return $campaign;
    }

    /**
     * Lấy danh sách người nhận theo tiêu chí
     */
    public function getRecipients(EmailCampaign $campaign)
    {
        $query = EmailSubscriber::active();

        if ($campaign->target_criteria) {
            foreach ($campaign->target_criteria as $key => $value) {
                switch ($key) {
                    case 'tags':
                        if (is_array($value)) {
                            foreach ($value as $tag) {
                                $query->whereJsonContains('tags', $tag);
                            }
                        } else {
                            $query->whereJsonContains('tags', $value);
                        }
                        break;
                    
                    case 'preferences':
                        if (is_array($value)) {
                            foreach ($value as $pref => $prefValue) {
                                $query->whereJsonContains('preferences->' . $pref, $prefValue);
                            }
                        }
                        break;
                    
                    case 'source':
                        $query->where('source', $value);
                        break;
                    
                    case 'subscribed_after':
                        $query->where('subscribed_at', '>=', Carbon::parse($value));
                        break;
                    
                    case 'subscribed_before':
                        $query->where('subscribed_at', '<=', Carbon::parse($value));
                        break;
                }
            }
        }

        return $query->get();
    }

    /**
     * Gửi chiến dịch email
     */
    public function sendCampaign(EmailCampaign $campaign)
    {
        if (!$campaign->canBeSent()) {
            throw new \Exception('Campaign cannot be sent at this time');
        }

        $campaign->update(['status' => 'sending']);
        
        $recipients = $this->getRecipients($campaign);
        $sentCount = 0;

        foreach ($recipients as $subscriber) {
            try {
                $this->sendEmailToSubscriber($campaign, $subscriber);
                $sentCount++;
                
                // Cập nhật tiến độ
                $campaign->update(['sent_count' => $sentCount]);
                
            } catch (\Exception $e) {
                Log::error('Failed to send email to subscriber', [
                    'campaign_id' => $campaign->id,
                    'subscriber_email' => $subscriber->email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $campaign->markAsSent();
        return $sentCount;
    }

    /**
     * Gửi email đến một subscriber cụ thể
     */
    public function sendEmailToSubscriber(EmailCampaign $campaign, EmailSubscriber $subscriber)
    {
        $data = $this->prepareEmailData($campaign, $subscriber);
        
        // Tạo log trước khi gửi
        $log = EmailLog::create([
            'campaign_id' => $campaign->id,
            'email' => $subscriber->email,
            'subject' => $data['subject'],
            'status' => 'sent',
            'sent_at' => now(),
            'metadata' => $data['metadata'] ?? [],
        ]);

        try {
            Mail::send('emails.' . $campaign->template, [
                'subject' => $data['subject'],
                'content' => $data['content'],
                'data' => $data,
            ], function ($message) use ($subscriber, $data) {
                $message->to($subscriber->email, $subscriber->name)
                        ->subject($data['subject']);
            });

            $log->markAsDelivered();
            
        } catch (\Exception $e) {
            $log->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    /**
     * Chuẩn bị dữ liệu email
     */
    public function prepareEmailData(EmailCampaign $campaign, EmailSubscriber $subscriber)
    {
        $data = [
            'subject' => $this->replacePlaceholders($campaign->subject, $subscriber),
            'content' => $this->replacePlaceholders($campaign->content, $subscriber),
            'reader_name' => $subscriber->name,
            'email' => $subscriber->email,
        ];

        // Thêm dữ liệu bổ sung từ metadata
        if ($campaign->metadata) {
            $data = array_merge($data, $campaign->metadata);
        }

        // Thêm sách nổi bật nếu có
        if (isset($data['include_featured_books']) && $data['include_featured_books']) {
            $data['featured_books'] = $this->getFeaturedBooks();
        }

        // Thêm thống kê thư viện nếu có
        if (isset($data['include_stats']) && $data['include_stats']) {
            $data['stats'] = $this->getLibraryStats();
        }

        return $data;
    }

    /**
     * Thay thế placeholder trong nội dung
     */
    public function replacePlaceholders($content, EmailSubscriber $subscriber)
    {
        $placeholders = [
            '{{name}}' => $subscriber->name,
            '{{email}}' => $subscriber->email,
            '{{library_name}}' => config('app.name', 'Thư viện'),
            '{{current_date}}' => Carbon::now()->format('d/m/Y'),
            '{{current_year}}' => Carbon::now()->year,
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $content);
    }

    /**
     * Lấy sách nổi bật
     */
    public function getFeaturedBooks($limit = 3)
    {
        return Book::with('author')
            ->where('trang_thai', 'Con hang')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($book) {
                return [
                    'title' => $book->ten_sach,
                    'author' => $book->author->ten_tac_gia ?? 'Không xác định',
                ];
            })
            ->toArray();
    }

    /**
     * Lấy thống kê thư viện
     */
    public function getLibraryStats()
    {
        return [
            [
                'number' => Book::count(),
                'label' => 'Tổng số sách'
            ],
            [
                'number' => Reader::count(),
                'label' => 'Số độc giả'
            ],
            [
                'number' => Book::where('trang_thai', 'Con hang')->count(),
                'label' => 'Sách có sẵn'
            ],
            [
                'number' => EmailSubscriber::active()->count(),
                'label' => 'Người đăng ký'
            ],
        ];
    }

    /**
     * Đăng ký email mới
     */
    public function subscribe($email, $name = null, $source = 'website', $tags = [], $preferences = [])
    {
        $subscriber = EmailSubscriber::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'status' => 'active',
                'subscribed_at' => now(),
                'source' => $source,
                'tags' => $tags,
                'preferences' => $preferences,
            ]
        );

        return $subscriber;
    }

    /**
     * Hủy đăng ký email
     */
    public function unsubscribe($email)
    {
        $subscriber = EmailSubscriber::where('email', $email)->first();
        if ($subscriber) {
            $subscriber->unsubscribe();
        }
        return $subscriber;
    }

    /**
     * Lấy thống kê chiến dịch
     */
    public function getCampaignStats(EmailCampaign $campaign)
    {
        $logs = $campaign->logs;
        
        return [
            'total_sent' => $logs->count(),
            'delivered' => $logs->where('status', 'delivered')->count(),
            'opened' => $logs->where('status', 'opened')->count(),
            'clicked' => $logs->where('status', 'clicked')->count(),
            'failed' => $logs->whereIn('status', ['failed', 'bounced'])->count(),
            'delivery_rate' => $logs->count() > 0 ? round(($logs->where('status', 'delivered')->count() / $logs->count()) * 100, 2) : 0,
            'open_rate' => $logs->where('status', 'delivered')->count() > 0 ? round(($logs->where('status', 'opened')->count() / $logs->where('status', 'delivered')->count()) * 100, 2) : 0,
            'click_rate' => $logs->where('status', 'opened')->count() > 0 ? round(($logs->where('status', 'clicked')->count() / $logs->where('status', 'opened')->count()) * 100, 2) : 0,
        ];
    }

    /**
     * Gửi email nhắc nhở trả sách với template marketing
     */
    public function sendMarketingReminder($borrowId)
    {
        $borrow = \App\Models\Borrow::with(['reader', 'book'])->find($borrowId);
        if (!$borrow) return false;

        $subscriber = EmailSubscriber::where('email', $borrow->reader->email)->first();
        if (!$subscriber) {
            // Tạo subscriber mới từ reader
            $subscriber = $this->subscribe(
                $borrow->reader->email,
                $borrow->reader->ho_ten,
                'library_system',
                ['reader', 'borrower']
            );
        }

        $data = [
            'subject' => '📚 Nhắc nhở trả sách - {{book_title}}',
            'content' => "Xin chào {{name}},\n\nSách '{{book_title}}' của bạn sắp đến hạn trả vào ngày {{due_date}}.\nCòn {{days_remaining}} ngày nữa.\n\nVui lòng trả sách đúng hạn để tránh phí phạt.\n\nTrân trọng,\nThư viện",
            'template' => 'marketing',
            'metadata' => [
                'book_title' => $borrow->book->ten_sach,
                'due_date' => $borrow->ngay_hen_tra->format('d/m/Y'),
                'days_remaining' => Carbon::today()->diffInDays($borrow->ngay_hen_tra, false),
                'action_url' => url('/reader/borrows'),
                'action_text' => 'Xem chi tiết mượn sách',
                'include_featured_books' => true,
                'include_stats' => false,
            ],
        ];

        $campaign = $this->createCampaign($data);
        $this->sendEmailToSubscriber($campaign, $subscriber);
        
        return true;
    }
}























