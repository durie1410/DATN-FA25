<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\NotificationTemplate;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $templates = NotificationTemplate::all();
        $overdueCount = Borrow::where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', Carbon::today())
            ->count();
        
        $dueSoonCount = Borrow::where('trang_thai', 'Dang muon')
            ->whereBetween('ngay_hen_tra', [Carbon::today(), Carbon::today()->addDays(3)])
            ->count();

        return view('admin.notifications.index', compact('templates', 'overdueCount', 'dueSoonCount'));
    }

    public function sendReminders(Request $request)
    {
        $type = $request->get('type', 'all');
        $sentCount = 0;

        switch ($type) {
            case 'due_soon':
                $sentCount = $this->sendDueSoonReminders();
                break;
            case 'overdue':
                $sentCount = $this->sendOverdueReminders();
                break;
            case 'all':
            default:
                $sentCount = $this->sendDueSoonReminders() + $this->sendOverdueReminders();
                break;
        }

        return redirect()->back()->with('success', "Đã gửi {$sentCount} thông báo nhắc nhở!");
    }

    private function sendDueSoonReminders()
    {
        $template = NotificationTemplate::where('type', 'borrow_reminder')->first();
        if (!$template) {
            return 0;
        }

        $borrows = Borrow::where('trang_thai', 'Dang muon')
            ->whereBetween('ngay_hen_tra', [Carbon::today(), Carbon::today()->addDays(3)])
            ->with(['reader', 'book'])
            ->get();

        $sentCount = 0;
        foreach ($borrows as $borrow) {
            $data = [
                'reader_name' => $borrow->reader->ho_ten,
                'book_title' => $borrow->book->ten_sach,
                'due_date' => $borrow->ngay_hen_tra->format('d/m/Y'),
                'days_remaining' => Carbon::today()->diffInDays($borrow->ngay_hen_tra, false),
            ];

            if ($this->notificationService->sendSimpleEmail(
                $borrow->reader->email,
                $template->subject,
                $template->content,
                $data
            )) {
                $sentCount++;
            }
        }

        return $sentCount;
    }

    private function sendOverdueReminders()
    {
        $template = NotificationTemplate::where('type', 'overdue_notification')->first();
        if (!$template) {
            return 0;
        }

        $borrows = Borrow::where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', Carbon::today())
            ->with(['reader', 'book'])
            ->get();

        $sentCount = 0;
        foreach ($borrows as $borrow) {
            $data = [
                'reader_name' => $borrow->reader->ho_ten,
                'book_title' => $borrow->book->ten_sach,
                'due_date' => $borrow->ngay_hen_tra->format('d/m/Y'),
                'days_overdue' => Carbon::today()->diffInDays($borrow->ngay_hen_tra),
            ];

            if ($this->notificationService->sendSimpleEmail(
                $borrow->reader->email,
                $template->subject,
                $template->content,
                $data
            )) {
                $sentCount++;
            }
        }

        return $sentCount;
    }

    public function sendCustomReminder(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'template_id' => 'required|exists:notification_templates,id',
            'custom_message' => 'nullable|string',
        ]);

        $borrow = Borrow::with(['reader', 'book'])->findOrFail($request->borrow_id);
        $template = NotificationTemplate::findOrFail($request->template_id);

        $data = [
            'reader_name' => $borrow->reader->ho_ten,
            'book_title' => $borrow->book->ten_sach,
            'due_date' => $borrow->ngay_hen_tra->format('d/m/Y'),
            'days_overdue' => Carbon::today()->diffInDays($borrow->ngay_hen_tra),
        ];

        $body = $request->custom_message ?: $template->content;

        if ($this->notificationService->sendSimpleEmail(
            $borrow->reader->email,
            $template->subject,
            $body,
            $data
        )) {
            return redirect()->back()->with('success', 'Đã gửi thông báo thành công!');
        }

        return redirect()->back()->withErrors(['error' => 'Gửi thông báo thất bại!']);
    }
}