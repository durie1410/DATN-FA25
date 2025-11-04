<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Borrow;
use App\Models\Reader;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $query = Fine::with(['borrow.book', 'reader', 'creator']);

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo loại phạt
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Lọc theo độc giả
        if ($request->filled('reader_id')) {
            $query->where('reader_id', $request->reader_id);
        }

        // Lọc phạt quá hạn
        if ($request->filled('overdue')) {
            $query->overdue();
        }

        $fines = $query->orderBy('created_at', 'desc')->paginate(20);
        $readers = Reader::all();

        return view('admin.fines.index', compact('fines', 'readers'));
    }

    public function create(Request $request)
    {
        $borrowId = $request->get('borrow_id');
        $borrow = null;
        
        if ($borrowId) {
            $borrow = Borrow::with(['book', 'reader'])->findOrFail($borrowId);
        }

        $borrows = Borrow::with(['book', 'reader'])
            ->where('trang_thai', 'Qua han')
            ->orWhere('trang_thai', 'Da tra')
            ->get();

        return view('admin.fines.create', compact('borrow', 'borrows'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:late_return,damaged_book,lost_book,other',
            'description' => 'nullable|string|max:500',
            'due_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        $borrow = Borrow::findOrFail($request->borrow_id);

        $fine = Fine::create([
            'borrow_id' => $request->borrow_id,
            'reader_id' => $borrow->reader_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        // Gửi thông báo cho độc giả
        try {
            $notificationService = new NotificationService();
            $data = [
                'reader_name' => $borrow->reader->ho_ten,
                'book_title' => $borrow->book->ten_sach,
                'fine_amount' => number_format($fine->amount, 0, ',', '.') . ' VND',
                'due_date' => $fine->due_date->format('d/m/Y'),
                'fine_type' => $this->getFineTypeText($fine->type),
            ];

            $notificationService->sendNotification(
                'fine_notification',
                $borrow->reader->email,
                $data,
                ['email', 'database']
            );
        } catch (\Exception $e) {
            // Log lỗi nhưng không làm gián đoạn quá trình tạo phạt
            \Log::error('Failed to send fine notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.fines.index')
            ->with('success', 'Phạt đã được tạo thành công!');
    }

    public function show($id)
    {
        $fine = Fine::with(['borrow.book', 'reader', 'creator'])->findOrFail($id);
        return view('admin.fines.show', compact('fine'));
    }

    public function edit($id)
    {
        $fine = Fine::findOrFail($id);
        return view('admin.fines.edit', compact('fine'));
    }

    public function update(Request $request, $id)
    {
        $fine = Fine::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:late_return,damaged_book,lost_book,other',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:pending,paid,waived,cancelled',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ];

        // Nếu đánh dấu là đã thanh toán, cập nhật ngày thanh toán
        if ($request->status === 'paid' && $fine->status !== 'paid') {
            $updateData['paid_date'] = Carbon::today();
        }

        $fine->update($updateData);

        return redirect()->route('admin.fines.show', $fine->id)
            ->with('success', 'Phạt đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $fine = Fine::findOrFail($id);
        $fine->delete();

        return back()->with('success', 'Phạt đã được xóa thành công!');
    }

    // Đánh dấu phạt là đã thanh toán
    public function markAsPaid($id)
    {
        $fine = Fine::findOrFail($id);
        
        $fine->update([
            'status' => 'paid',
            'paid_date' => Carbon::today(),
        ]);

        return back()->with('success', 'Phạt đã được đánh dấu là đã thanh toán!');
    }

    // Miễn phạt
    public function waive($id)
    {
        $fine = Fine::findOrFail($id);
        
        $fine->update([
            'status' => 'waived',
            'notes' => $fine->notes . "\n[Miễn phạt bởi " . Auth::user()->name . " vào " . Carbon::now() . "]",
        ]);

        return back()->with('success', 'Phạt đã được miễn!');
    }

    // Tự động tạo phạt cho sách trả muộn
    public function createLateReturnFines()
    {
        $overdueBorrows = Borrow::with(['book', 'reader'])
            ->where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', Carbon::today())
            ->get();

        $createdCount = 0;
        $errors = [];

        foreach ($overdueBorrows as $borrow) {
            try {
                // Kiểm tra đã có phạt chưa
                $existingFine = Fine::where('borrow_id', $borrow->id)
                    ->where('type', 'late_return')
                    ->where('status', 'pending')
                    ->first();

                if (!$existingFine) {
                    $daysOverdue = Carbon::today()->diffInDays($borrow->ngay_hen_tra);
                    $fineAmount = $daysOverdue * 5000; // 5000 VND/ngày

                    Fine::create([
                        'borrow_id' => $borrow->id,
                        'reader_id' => $borrow->reader_id,
                        'amount' => $fineAmount,
                        'type' => 'late_return',
                        'description' => "Trả sách muộn {$daysOverdue} ngày (hạn trả: {$borrow->ngay_hen_tra->format('d/m/Y')})",
                        'due_date' => Carbon::today()->addDays(30), // Cho 30 ngày để thanh toán
                        'created_by' => Auth::id(),
                        'notes' => 'Tự động tạo bởi hệ thống'
                    ]);

                    // Gửi thông báo cho độc giả
                    try {
                        $notificationService = new NotificationService();
                        $data = [
                            'reader_name' => $borrow->reader->ho_ten,
                            'book_title' => $borrow->book->ten_sach,
                            'fine_amount' => number_format($fineAmount, 0, ',', '.') . ' VND',
                            'due_date' => Carbon::today()->addDays(30)->format('d/m/Y'),
                            'fine_type' => 'Trả sách muộn',
                        ];

                        $notificationService->sendNotification(
                            'fine_notification',
                            $borrow->reader->email,
                            $data,
                            ['email', 'database']
                        );
                    } catch (\Exception $e) {
                        \Log::error('Failed to send fine notification for borrow #' . $borrow->id . ': ' . $e->getMessage());
                    }

                    $createdCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Lỗi khi tạo phạt cho phiếu mượn #{$borrow->id}: " . $e->getMessage();
            }
        }

        $response = [
            'status' => 'success',
            'message' => "Đã tạo {$createdCount} phạt trả muộn mới",
            'created_count' => $createdCount
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
            $response['message'] .= " (Có " . count($errors) . " lỗi)";
        }

        return response()->json($response);
    }

    // Báo cáo phạt
    public function report(Request $request)
    {
        $query = Fine::with(['borrow.book', 'reader']);

        // Lọc theo khoảng thời gian
        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }

        $fines = $query->get();

        $stats = [
            'total_fines' => $fines->count(),
            'total_amount' => $fines->sum('amount'),
            'pending_amount' => $fines->where('status', 'pending')->sum('amount'),
            'paid_amount' => $fines->where('status', 'paid')->sum('amount'),
            'waived_amount' => $fines->where('status', 'waived')->sum('amount'),
            'overdue_count' => $fines->where('status', 'pending')->filter(function($fine) {
                return $fine->isOverdue();
            })->count(),
        ];

        return view('admin.fines.report', compact('fines', 'stats'));
    }

    /**
     * Lấy text cho loại phạt
     */
    private function getFineTypeText($type)
    {
        $types = [
            'late_return' => 'Trả sách muộn',
            'damaged_book' => 'Làm hỏng sách',
            'lost_book' => 'Mất sách',
            'other' => 'Khác',
        ];

        return $types[$type] ?? 'Không xác định';
    }
}