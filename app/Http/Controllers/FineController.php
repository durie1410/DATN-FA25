<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Borrow;
use App\Models\Reader;
use App\Models\BorrowItem;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FineController extends Controller
{
    // Danh sách phạt
    public function index(Request $request)
{
    // Lấy tất cả fines, kể cả đã xoá mềm
    // Load cả borrowItem (nếu có) và borrow với items để fallback cho dữ liệu cũ
    $query = Fine::withTrashed();
    
    // Eager load các quan hệ - không dùng whereHas để không loại bỏ dữ liệu cũ
    $query->with([
        'borrowItem.book', 
        'borrowItem.inventory',
        'borrow.borrowItems.book', // Load items từ borrow để fallback cho dữ liệu cũ
        'borrow.reader',
        'reader', 
        'creator',
        'inspector'
    ]);

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

    // Lọc chỉ quá hạn - chỉ áp dụng khi có request overdue
    if ($request->filled('overdue') && $request->overdue == '1') {
        $query->where('due_date', '<', Carbon::today())
              ->where('status', 'pending');
    }

    // Tìm kiếm theo tên độc giả hoặc mã số thẻ
    if ($request->filled('search')) {
        $search = $request->search;
        // Dùng leftJoin để không loại bỏ fines không có reader
        $query->where(function($q) use ($search) {
            $q->whereHas('reader', function($subQ) use ($search) {
                $subQ->where('ho_ten', 'like', "%{$search}%")
                     ->orWhere('ma_so_the', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%"); // Tìm theo ID nếu không có reader
        });
    }

    // Sắp xếp
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);

    $fines = $query->paginate(20)->appends($request->query());
    $readers = Reader::orderBy('ho_ten')->get();

    // Thống kê nhanh - dùng withTrashed để đếm cả dữ liệu đã xóa
    $stats = [
        'total' => Fine::withTrashed()->count(),
        'pending' => Fine::withTrashed()->where('status', 'pending')->count(),
        'paid' => Fine::withTrashed()->where('status', 'paid')->count(),
        'overdue' => Fine::withTrashed()
                        ->where('due_date', '<', Carbon::today())
                        ->where('status', 'pending')
                        ->count(),
        'total_amount' => Fine::withTrashed()
                        ->where('status', 'pending')
                        ->sum('amount') ?? 0,
    ];

    return view('admin.fines.index', compact('fines', 'readers', 'stats'));
}

    // Form tạo phạt mới
    public function create(Request $request)
    {
        $borrowItemId = $request->get('borrow_item_id');
        $borrowItem = null;

        if ($borrowItemId) {
            $borrowItem = BorrowItem::with(['book', 'borrow.reader', 'inventory'])->findOrFail($borrowItemId);
        }

        // Lấy tất cả borrow items có thể tạo phạt (quá hạn, đã trả, hoặc mất sách)
        $borrowItems = BorrowItem::with(['book', 'borrow.reader', 'inventory'])
            ->whereIn('trang_thai', ['Qua han', 'Da tra', 'Mat sach'])
            ->orWhere(function($query) {
                $query->where('trang_thai', 'Dang muon')
                      ->whereDate('ngay_hen_tra', '<', Carbon::today());
            })
            ->orderBy('ngay_hen_tra', 'desc')
            ->get();

        return view('admin.fines.create', compact('borrowItem', 'borrowItems'));
    }

    // Lưu phạt mới
    public function store(Request $request)
    {
        $request->validate([
            'borrow_item_id' => 'required|exists:borrow_items,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:late_return,damaged_book,lost_book,other',
            'description' => 'nullable|string|max:500',
            'damage_description' => 'nullable|string|max:1000',
            'damage_severity' => 'nullable|in:nhe,trung_binh,nang,mat_sach',
            'damage_type' => 'nullable|string|max:255',
            'condition_before' => 'nullable|string|max:255',
            'condition_after' => 'nullable|string|max:255',
            'damage_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB per image
            'inspection_notes' => 'nullable|string|max:1000',
            'due_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        $borrowItem = BorrowItem::with(['borrow.reader', 'book'])->findOrFail($request->borrow_item_id);
        $borrow = $borrowItem->borrow;

        if (!$borrow) {
            return back()->withErrors(['borrow_item_id' => 'Không tìm thấy phiếu mượn liên quan.'])->withInput();
        }

        // Kiểm tra xem đã có phạt cho borrow_item này chưa (nếu là loại late_return)
        if ($request->type === 'late_return') {
            $existingFine = Fine::where('borrow_item_id', $borrowItem->id)
                ->where('type', 'late_return')
                ->where('status', 'pending')
                ->first();
            
            if ($existingFine) {
                return back()->withErrors(['borrow_item_id' => 'Đã tồn tại phạt trả muộn cho sách này.'])->withInput();
            }
        }

        // Xử lý upload ảnh hư hỏng
        $damageImages = [];
        if ($request->hasFile('damage_images')) {
            foreach ($request->file('damage_images') as $image) {
                $path = $image->store('fines/damage_images', 'public');
                $damageImages[] = $path;
            }
        }

        // Lấy tình trạng sách trước khi mượn từ inventory
        $conditionBefore = null;
        if ($borrowItem->inventory) {
            $conditionBefore = $borrowItem->inventory->condition;
        }

        $fine = Fine::create([
            'borrow_id' => $borrow->id,
            'borrow_item_id' => $borrowItem->id,
            'reader_id' => $borrow->reader_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'damage_description' => $request->damage_description,
            'damage_images' => !empty($damageImages) ? $damageImages : null,
            'damage_severity' => $request->damage_severity,
            'damage_type' => $request->damage_type,
            'condition_before' => $request->condition_before ?? $conditionBefore,
            'condition_after' => $request->condition_after,
            'inspected_by' => Auth::id(),
            'inspected_at' => now(),
            'inspection_notes' => $request->inspection_notes,
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
                'book_name' => $borrowItem->book->ten_sach ?? 'N/A',
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
        'borrowItem.book',
        'borrowItem.inventory',
        'borrow.borrowItems.book', // Fallback cho dữ liệu cũ
        'borrow.reader',
        'reader',
        'creator'
    ])->findOrFail($id);

    return view('admin.fines.show', compact('fine'));
}

    // Form edit
    public function edit($id)
    {
        $fine = Fine::with([
            'borrowItem.book',
            'borrowItem.inventory',
            'borrow.borrowItems.book', // Fallback cho dữ liệu cũ
            'borrow.reader',
            'reader',
            'creator'
        ])->findOrFail($id);
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
            'damage_description' => 'nullable|string|max:1000',
            'damage_severity' => 'nullable|in:nhe,trung_binh,nang,mat_sach',
            'damage_type' => 'nullable|string|max:255',
            'condition_before' => 'nullable|string|max:255',
            'condition_after' => 'nullable|string|max:255',
            'damage_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'inspection_notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,paid,waived,cancelled',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Xử lý upload ảnh hư hỏng mới (nếu có)
        $damageImages = $fine->damage_images ?? [];
        if ($request->hasFile('damage_images')) {
            foreach ($request->file('damage_images') as $image) {
                $path = $image->store('fines/damage_images', 'public');
                $damageImages[] = $path;
            }
        }

        // Xóa ảnh nếu có request
        if ($request->filled('remove_images')) {
            $removeImages = is_array($request->remove_images) ? $request->remove_images : [$request->remove_images];
            foreach ($removeImages as $imagePath) {
                if (($key = array_search($imagePath, $damageImages)) !== false) {
                    // Xóa file khỏi storage
                    if (\Storage::disk('public')->exists($imagePath)) {
                        \Storage::disk('public')->delete($imagePath);
                    }
                    unset($damageImages[$key]);
                }
            }
            $damageImages = array_values($damageImages); // Re-index array
        }

        $updateData = [
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'damage_description' => $request->damage_description,
            'damage_images' => !empty($damageImages) ? $damageImages : null,
            'damage_severity' => $request->damage_severity,
            'damage_type' => $request->damage_type,
            'condition_before' => $request->condition_before,
            'condition_after' => $request->condition_after,
            'inspection_notes' => $request->inspection_notes,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ];

        // Cập nhật thông tin kiểm tra nếu có thay đổi về hư hỏng
        if ($request->filled('damage_description') || $request->hasFile('damage_images')) {
            $updateData['inspected_by'] = Auth::id();
            $updateData['inspected_at'] = now();
        }

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
    $overdueItems = BorrowItem::with(['borrow.reader', 'book'])
        ->where('trang_thai', 'Dang muon')
        ->whereDate('ngay_hen_tra', '<', $today)
        ->get();

    $createdCount = 0;
    $updatedCount = 0;
    $skippedCount = 0;

    foreach ($overdueItems as $item) {
        $borrow = $item->borrow;

        if (!$borrow) {
            $skippedCount++;
            continue;
        }

        // Kiểm tra đã có phạt trả muộn cho borrow_item này chưa (pending)
        $existingFine = Fine::where('borrow_item_id', $item->id)
            ->where('type', 'late_return')
            ->where('status', 'pending')
            ->first();

        // Số ngày quá hạn
        $daysOverdue = $today->diffInDays(Carbon::parse($item->ngay_hen_tra), false) * -1;
        if ($daysOverdue <= 0) {
            $skippedCount++;
            continue;
        }

        $fineAmount = $daysOverdue * 5000; // 5000 VND/ngày

        if ($existingFine) {
            // Cập nhật số tiền phạt nếu đã tồn tại
            $existingFine->amount = $fineAmount;
            $existingFine->description = "Trả sách muộn {$daysOverdue} ngày (hạn trả: " . Carbon::parse($item->ngay_hen_tra)->format('d/m/Y') . ")";
            $existingFine->due_date = $today->copy()->addDays(30);
            $existingFine->save();
            $updatedCount++;
        } else {
            // Tạo mới phạt trả muộn
            Fine::create([
                'borrow_id' => $borrow->id,
                'borrow_item_id' => $item->id,
                'reader_id' => $borrow->reader_id,
                'amount' => $fineAmount,
                'type' => 'late_return',
                'description' => "Trả sách muộn {$daysOverdue} ngày (hạn trả: " . Carbon::parse($item->ngay_hen_tra)->format('d/m/Y') . ")",
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
        'message' => "Đã tạo {$createdCount} phạt mới, cập nhật {$updatedCount} phạt hiện có, bỏ qua {$skippedCount} mục."
    ]);
}
    // Báo cáo phạt
    public function report(Request $request)
    {
        $query = Fine::with([
            'borrowItem.book',
            'borrowItem.inventory',
            'borrow.borrowItems.book', // Fallback cho dữ liệu cũ
            'borrow.reader',
            'reader',
            'creator'
        ]);

        // Lọc theo ngày tạo
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo loại phạt
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $fines = $query->orderBy('created_at', 'desc')->get();

        $today = Carbon::today();
        $stats = [
            'total_fines' => $fines->count(),
            'total_amount' => $fines->sum('amount'),
            'pending_amount' => $fines->where('status','pending')->sum('amount'),
            'paid_amount' => $fines->where('status','paid')->sum('amount'),
            'waived_amount' => $fines->where('status','waived')->sum('amount'),
            'cancelled_amount' => $fines->where('status','cancelled')->sum('amount'),
            'overdue_count' => $fines->where('status','pending')
                ->filter(fn($f) => $f->due_date < $today)->count(),
            'overdue_amount' => $fines->where('status','pending')
                ->filter(fn($f) => $f->due_date < $today)->sum('amount'),
            // Thống kê theo loại
            'late_return_count' => $fines->where('type', 'late_return')->count(),
            'damaged_book_count' => $fines->where('type', 'damaged_book')->count(),
            'lost_book_count' => $fines->where('type', 'lost_book')->count(),
            'other_count' => $fines->where('type', 'other')->count(),
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
