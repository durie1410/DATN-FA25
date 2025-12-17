<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Borrow;
use App\Models\Reader;
use App\Models\BorrowItem;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FineController extends Controller
{
    // Danh sách phạt
    public function index(Request $request)
{
    // Lấy tất cả fines, kể cả đã xoá mềm
    $query = Fine::withTrashed()->with(['borrowItem.book', 'reader', 'creator']);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('reader_id')) {
        $query->where('reader_id', $request->reader_id);
    }

    $fines = $query->orderBy('created_at', 'desc')->paginate(20);
    $readers = Reader::all();

    return view('admin.fines.index', compact('fines', 'readers'));
}

    // Form tạo phạt mới
    public function create(Request $request)
    {
        $borrowId = $request->get('borrow_id');
        $borrow = null;

        if ($borrowId) {
            $borrow = Borrow::with(['reader'])->findOrFail($borrowId);
        }

        // Lấy tất cả borrow quá hạn hoặc đã trả
        $borrows = Borrow::with(['reader'])
            ->where('trang_thai', 'Qua han')
            ->orWhere('trang_thai', 'Da tra')
            ->get();

        return view('admin.fines.create', compact('borrow', 'borrows'));
    }

    // Lưu phạt mới
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
            'borrow_id' => $borrow->id,
            'reader_id' => $borrow->reader_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        // Gửi thông báo
        try {
            $notificationService = new NotificationService();
            $data = [
                'reader_name' => $borrow->reader->ho_ten,
                'borrow_id' => $borrow->id,
                'fine_amount' => number_format($fine->amount, 0, ',', '.') . ' VND',
                'due_date' => Carbon::parse($fine->due_date)->format('d/m/Y'),
                'fine_type' => $this->getFineTypeText($fine->type),
            ];
            $notificationService->sendNotification(
                'fine_notification',
                $borrow->reader->email,
                $data,
                ['email', 'database']
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send fine notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.fines.index')->with('success', 'Phạt đã được tạo thành công!');
    }

    // Xem chi tiết
    public function show($id)
{
    $fine = Fine::with([
        'borrowItem.book',   // cần cái này
        'reader',
        'creator'
    ])->findOrFail($id);

    return view('admin.fines.show', compact('fine'));
}

    // Form edit
    public function edit($id)
    {
        $fine = Fine::findOrFail($id);
        return view('admin.fines.edit', compact('fine'));
    }

    // Cập nhật phạt
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

        if ($request->status === 'paid' && $fine->status !== 'paid') {
            $updateData['paid_date'] = Carbon::today();
        }

        $fine->update($updateData);

        return redirect()->route('admin.fines.show', $fine->id)->with('success', 'Phạt đã được cập nhật thành công!');
    }

    // Xóa phạt
    public function destroy($id)
    {
        $fine = Fine::findOrFail($id);
        $fine->delete();
        return back()->with('success', 'Phạt đã được xóa thành công!');
    }

    // Đánh dấu đã thanh toán
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
        'notes' => ($fine->notes ?? '') . "\n[Miễn phạt bởi " . Auth::user()->name . " vào " . Carbon::now() . "]",
    ]);

    // Xoá mềm
    $fine->delete();

    // Nếu muốn xoá luôn borrow_item liên quan:
    if ($fine->borrowItem) {
        $fine->borrowItem->delete();
    }

    return back()->with('success', 'Phạt đã được miễn và xoá mềm!');
}

// Khôi phục fine đã xoá mềm
public function restore($id)
{
    $fine = Fine::withTrashed()->findOrFail($id);

    if ($fine->trashed()) {
        $fine->restore();
        return back()->with('success', 'Phạt đã được khôi phục!');
    }

    return back()->with('info', 'Phạt chưa bị xoá mềm.');
}


    // Tạo phạt trả muộn tự động
   
public function createLateReturnFines()
{
    $today = Carbon::today();

    // Lấy tất cả borrow_items đang mượn và quá hạn
    $overdueItems = BorrowItem::with('borrow.reader')
        ->where('trang_thai', 'Dang muon')
        ->whereDate('ngay_hen_tra', '<', $today)
        ->get();

    $createdCount = 0;
    $updatedCount = 0;

    foreach ($overdueItems as $item) {
        $borrow = $item->borrow;

        if (!$borrow) continue;

        // Kiểm tra đã có phạt trả muộn chưa (pending)
        $existingFine = Fine::where('borrow_id', $borrow->id)
            ->where('type', 'late_return')
            ->where('status', 'pending')
            ->first();

        // Số ngày quá hạn
        $daysOverdue = $today->diffInDays($item->ngay_hen_tra, false) * -1;
        if ($daysOverdue <= 0) continue;

        $fineAmount = $daysOverdue * 5000; // 5000 VND/ngày

        if ($existingFine) {
            // Cập nhật số tiền phạt nếu đã tồn tại
            $existingFine->amount = $fineAmount;
            $existingFine->description = "Trả sách muộn {$daysOverdue} ngày (hạn trả: {$item->ngay_hen_tra})";
            $existingFine->save();
            $updatedCount++;
        } else {
            // Tạo mới phạt trả muộn
            Fine::create([
                'borrow_id' => $borrow->id,
                'reader_id' => $borrow->reader_id,
                'amount' => $fineAmount,
                'type' => 'late_return',
                'description' => "Trả sách muộn {$daysOverdue} ngày (hạn trả: {$item->ngay_hen_tra})",
                'due_date' => $today->copy()->addDays(30),
                'status' => 'pending',
                'notes' => 'Tự động tạo bởi hệ thống',
                'created_by' => Auth::id(),
            ]);
            $createdCount++;
        }
    }

    return response()->json([
        'status' => 'success',
        'message' => "Đã tạo {$createdCount} phạt mới và cập nhật {$updatedCount} phạt hiện có."
    ]);
}
    // Báo cáo phạt
    public function report(Request $request)
    {
        $query = Fine::with(['borrow', 'reader']);

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
            'pending_amount' => $fines->where('status','pending')->sum('amount'),
            'paid_amount' => $fines->where('status','paid')->sum('amount'),
            'waived_amount' => $fines->where('status','waived')->sum('amount'),
            'overdue_count' => $fines->where('status','pending')->filter(fn($f)=>Carbon::today()->gt(Carbon::parse($f->due_date)))->count(),
        ];

        return view('admin.fines.report', compact('fines','stats'));
    }

    private function getFineTypeText($type)
    {
        return match($type) {
            'late_return' => 'Trả sách muộn',
            'damaged_book' => 'Làm hỏng sách',
            'lost_book' => 'Mất sách',
            'other' => 'Khác',
            default => 'Không xác định',
        };
    }
}
