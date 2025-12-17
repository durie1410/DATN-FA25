<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\EmailSubscriber;
use App\Models\EmailLog;
use App\Services\EmailMarketingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmailMarketingController extends Controller
{
    protected $emailMarketingService;

    public function __construct(EmailMarketingService $emailMarketingService)
    {
        $this->emailMarketingService = $emailMarketingService;
    }

    /**
     * Hiển thị danh sách chiến dịch email marketing
     */
    public function index()
    {
        $campaigns = EmailCampaign::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_campaigns' => EmailCampaign::count(),
            'active_subscribers' => EmailSubscriber::active()->count(),
            'total_emails_sent' => EmailLog::count(),
            'open_rate' => $this->getOverallOpenRate(),
        ];

        return view('admin.email-marketing.index', compact('campaigns', 'stats'));
    }

    /**
     * Hiển thị form tạo chiến dịch mới
     */
    public function create()
    {
        $templates = ['marketing', 'simple', 'notification'];
        $tags = EmailSubscriber::whereNotNull('tags')->pluck('tags')->flatten()->unique()->values();
        
        return view('admin.email-marketing.create', compact('templates', 'tags'));
    }

    /**
     * Lưu chiến dịch mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'template' => 'required|string|in:marketing,simple,notification',
            'scheduled_at' => 'nullable|date|after:now',
            'target_criteria' => 'nullable|array',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();

        $campaign = $this->emailMarketingService->createCampaign($data);

        return redirect()->route('admin.email-marketing.show', $campaign->id)
            ->with('success', 'Chiến dịch đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết chiến dịch
     */
    public function show($id)
    {
        $campaign = EmailCampaign::with(['creator', 'logs'])->findOrFail($id);
        $stats = $this->emailMarketingService->getCampaignStats($campaign);
        $recipients = $this->emailMarketingService->getRecipients($campaign);
        
        return view('admin.email-marketing.show', compact('campaign', 'stats', 'recipients'));
    }

    /**
     * Hiển thị form chỉnh sửa chiến dịch
     */
    public function edit($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if ($campaign->status !== 'draft') {
            return redirect()->back()->withErrors(['error' => 'Chỉ có thể chỉnh sửa chiến dịch ở trạng thái draft']);
        }

        $templates = ['marketing', 'simple', 'notification'];
        $tags = EmailSubscriber::whereNotNull('tags')->pluck('tags')->flatten()->unique()->values();
        
        return view('admin.email-marketing.edit', compact('campaign', 'templates', 'tags'));
    }

    /**
     * Cập nhật chiến dịch
     */
    public function update(Request $request, $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if ($campaign->status !== 'draft') {
            return redirect()->back()->withErrors(['error' => 'Chỉ có thể chỉnh sửa chiến dịch ở trạng thái draft']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'template' => 'required|string|in:marketing,simple,notification',
            'scheduled_at' => 'nullable|date|after:now',
            'target_criteria' => 'nullable|array',
        ]);

        $campaign->update($request->all());

        // Cập nhật số lượng người nhận
        $recipients = $this->emailMarketingService->getRecipients($campaign);
        $campaign->update(['total_recipients' => $recipients->count()]);

        return redirect()->route('admin.email-marketing.show', $campaign->id)
            ->with('success', 'Chiến dịch đã được cập nhật thành công!');
    }

    /**
     * Xóa chiến dịch
     */
    public function destroy($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if ($campaign->status === 'sending') {
            return redirect()->back()->withErrors(['error' => 'Không thể xóa chiến dịch đang được gửi']);
        }

        $campaign->delete();

        return redirect()->route('admin.email-marketing.index')
            ->with('success', 'Chiến dịch đã được xóa thành công!');
    }

    /**
     * Gửi chiến dịch ngay lập tức
     */
    public function sendNow($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if (!$campaign->canBeSent()) {
            return redirect()->back()->withErrors(['error' => 'Chiến dịch không thể được gửi vào lúc này']);
        }

        try {
            $sentCount = $this->emailMarketingService->sendCampaign($campaign);
            
            return redirect()->back()->with('success', "Đã gửi {$sentCount} email thành công!");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Lỗi khi gửi email: ' . $e->getMessage()]);
        }
    }

    /**
     * Lên lịch gửi chiến dịch
     */
    public function schedule(Request $request, $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        $campaign->update([
            'status' => 'scheduled',
            'scheduled_at' => Carbon::parse($request->scheduled_at),
        ]);

        return redirect()->back()->with('success', 'Chiến dịch đã được lên lịch thành công!');
    }

    /**
     * Hủy chiến dịch
     */
    public function cancel($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if ($campaign->status === 'sent') {
            return redirect()->back()->withErrors(['error' => 'Không thể hủy chiến dịch đã được gửi']);
        }

        $campaign->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Chiến dịch đã được hủy thành công!');
    }

    /**
     * Quản lý subscribers
     */
    public function subscribers()
    {
        $subscribers = EmailSubscriber::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => EmailSubscriber::count(),
            'active' => EmailSubscriber::active()->count(),
            'unsubscribed' => EmailSubscriber::unsubscribed()->count(),
        ];

        return view('admin.email-marketing.subscribers', compact('subscribers', 'stats'));
    }

    /**
     * Thêm subscriber mới
     */
    public function addSubscriber(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:email_subscribers,email',
            'name' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'preferences' => 'nullable|array',
        ]);

        $subscriber = $this->emailMarketingService->subscribe(
            $request->email,
            $request->name,
            'admin_manual',
            $request->tags ?? [],
            $request->preferences ?? []
        );

        return redirect()->back()->with('success', 'Subscriber đã được thêm thành công!');
    }

    /**
     * Hủy đăng ký subscriber
     */
    public function unsubscribeSubscriber($id)
    {
        $subscriber = EmailSubscriber::findOrFail($id);
        $subscriber->unsubscribe();

        return redirect()->back()->with('success', 'Subscriber đã được hủy đăng ký thành công!');
    }

    /**
     * Lấy tỷ lệ mở email tổng thể
     */
    private function getOverallOpenRate()
    {
        $totalSent = EmailLog::count();
        $totalOpened = EmailLog::opened()->count();
        
        return $totalSent > 0 ? round(($totalOpened / $totalSent) * 100, 2) : 0;
    }
}