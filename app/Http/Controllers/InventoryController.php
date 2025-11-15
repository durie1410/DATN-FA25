<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\InventoryReceipt;
use App\Models\DisplayAllocation;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use App\Exports\InventoryExport;
use App\Services\FileUploadService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['book', 'creator']);

        // Lọc theo sách
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo tình trạng
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Lọc theo vị trí
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Tìm kiếm theo mã vạch
        if ($request->filled('barcode')) {
            $query->where('barcode', 'like', "%{$request->barcode}%");
        }

        // Tìm kiếm theo tên sách
        if ($request->filled('book_title')) {
            $query->whereHas('book', function($bookQuery) use ($request) {
                $bookQuery->where('ten_sach', 'like', "%{$request->book_title}%");
            });
        }

        // Lọc theo loại lưu trữ (Kho hoặc Trưng bày)
        if ($request->filled('storage_type')) {
            $query->where('storage_type', $request->storage_type);
        }

        $inventories = $query->orderBy('created_at', 'desc')->paginate(20);
        $books = Book::all();

        return view('admin.inventory.index', compact('inventories', 'books'));
    }

    public function create()
    {
        $books = Book::all();
        $categories = \App\Models\Category::all();
        $publishers = \App\Models\Publisher::all();
        
        // Lấy danh sách vị trí đã sử dụng kèm số lượng sách, phân loại theo storage_type
        $locationsInStock = Inventory::where('storage_type', 'Kho')
            ->select('location', DB::raw('COUNT(*) as book_count'))
            ->groupBy('location')
            ->orderBy('location')
            ->get()
            ->map(function($item) {
                return [
                    'location' => $item->location,
                    'count' => $item->book_count
                ];
            })
            ->toArray();
            
        $locationsOnDisplay = Inventory::where('storage_type', 'Trung bay')
            ->select('location', DB::raw('COUNT(*) as book_count'))
            ->groupBy('location')
            ->orderBy('location')
            ->get()
            ->map(function($item) {
                return [
                    'location' => $item->location,
                    'count' => $item->book_count
                ];
            })
            ->toArray();
        
        return view('admin.inventory.create', compact(
            'books', 
            'categories', 
            'publishers',
            'locationsInStock',
            'locationsOnDisplay'
        ));
    }

    public function store(Request $request)
    {
        $bookInputType = $request->book_input_type ?? 'existing';
        
        if ($bookInputType === 'new') {
            // Validate cho sách mới
            $request->validate([
                'ten_sach' => 'required|string|max:255',
                'tac_gia' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'barcode' => 'nullable|string|max:100|unique:inventories',
                'location' => 'required|string|max:100',
                'storage_type' => 'required|in:Kho,Trung bay',
                'condition' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
                'status' => 'required|in:Co san,Dang muon,Mat,Hong,Thanh ly',
                'purchase_price' => 'nullable|numeric|min:0',
                'purchase_date' => 'nullable|date',
                'notes' => 'nullable|string|max:500',
                'nha_xuat_ban_id' => 'nullable|exists:publishers,id',
                'nam_xuat_ban' => 'nullable|integer|min:1900|max:' . date('Y'),
                'gia' => 'nullable|numeric|min:0',
                'mo_ta' => 'nullable|string',
            ]);
        } else {
            // Validate cho sách có sẵn
            $request->validate([
                'book_id' => 'required|exists:books,id',
                'barcode' => 'nullable|string|max:100|unique:inventories',
                'location' => 'required|string|max:100',
                'storage_type' => 'required|in:Kho,Trung bay',
                'condition' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
                'status' => 'required|in:Co san,Dang muon,Mat,Hong,Thanh ly',
                'purchase_price' => 'nullable|numeric|min:0',
                'purchase_date' => 'nullable|date',
                'notes' => 'nullable|string|max:500',
            ]);
        }

        DB::beginTransaction();
        try {
            // Nếu là sách mới, tạo Book trước
            if ($bookInputType === 'new') {
                $book = Book::create([
                    'ten_sach' => $request->ten_sach,
                    'tac_gia' => $request->tac_gia,
                    'category_id' => $request->category_id,
                    'nha_xuat_ban_id' => $request->nha_xuat_ban_id,
                    'nam_xuat_ban' => $request->nam_xuat_ban ?? date('Y'),
                    'gia' => $request->gia ?? $request->purchase_price ?? 0,
                    'mo_ta' => $request->mo_ta,
                    'trang_thai' => 'active',
                    'danh_gia_trung_binh' => 0,
                    'so_luong_ban' => 0,
                    'so_luot_xem' => 0,
                ]);
                $bookId = $book->id;
            } else {
                $bookId = $request->book_id;
            }

            // Tạo mã vạch tự động nếu không có
            $barcode = $request->barcode;
            if (!$barcode) {
                $barcode = 'BK' . str_pad(Inventory::count() + 1, 6, '0', STR_PAD_LEFT);
            }

            $inventory = Inventory::create([
                'book_id' => $bookId,
                'barcode' => $barcode,
                'location' => $request->location,
                'storage_type' => $request->storage_type,
                'condition' => $request->condition,
                'status' => $request->status,
                'purchase_price' => $request->purchase_price,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            // Tạo transaction nhập kho
            InventoryTransaction::create([
                'inventory_id' => $inventory->id,
                'type' => 'Nhap kho',
                'quantity' => 1,
                'to_location' => $request->location,
                'condition_after' => $request->condition,
                'status_after' => $request->status,
                'reason' => $bookInputType === 'new' ? 'Nhập kho sách mới' : 'Nhập kho mới',
                'notes' => $request->notes,
                'performed_by' => Auth::id(),
            ]);

            DB::commit();

            $message = 'Sách đã được thêm vào kho thành công!';
            if ($bookInputType === 'new') {
                $message = 'Sách mới đã được tạo và thêm vào kho thành công!';
            }

            return redirect()->route('admin.inventory.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $inventory = Inventory::with(['book', 'creator', 'transactions.performer'])
            ->findOrFail($id);

        return view('admin.inventory.show', compact('inventory'));
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $books = Book::all();

        return view('admin.inventory.edit', compact('inventory', 'books'));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'location' => 'required|string|max:100',
            'storage_type' => 'required|in:Kho,Trung bay',
            'condition' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
            'status' => 'required|in:Co san,Dang muon,Mat,Hong,Thanh ly',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
            'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $oldLocation = $inventory->location;
        $oldStorageType = $inventory->storage_type;
        $oldCondition = $inventory->condition;
        $oldStatus = $inventory->status;

        // Xử lý upload ảnh
        $imagePath = $inventory->hinh_anh; // Giữ ảnh cũ nếu không upload ảnh mới
        
        if ($request->hasFile('hinh_anh')) {
            try {
                // Xóa ảnh cũ nếu có
                if ($inventory->hinh_anh && Storage::disk('public')->exists($inventory->hinh_anh)) {
                    FileUploadService::deleteFile($inventory->hinh_anh, 'public');
                }
                
                // Upload ảnh mới - đảm bảo directory không rỗng
                $result = FileUploadService::uploadImage(
                    $request->file('hinh_anh'),
                    'inventory', // Directory name - không được rỗng
                    [
                        'max_size' => 2048, // 2MB
                        'resize' => true,
                        'width' => 800,
                        'height' => 800,
                        'disk' => 'public',
                    ]
                );
                $imagePath = $result['path'];
            } catch (\Exception $e) {
                \Log::error('Upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()
                    ->withErrors(['hinh_anh' => $e->getMessage()])
                    ->withInput();
            }
        }

        $inventory->update([
            'location' => $request->location,
            'storage_type' => $request->storage_type,
            'condition' => $request->condition,
            'status' => $request->status,
            'purchase_price' => $request->purchase_price,
            'purchase_date' => $request->purchase_date,
            'notes' => $request->notes,
            'hinh_anh' => $imagePath,
        ]);

        // Tạo transaction nếu có thay đổi
        if ($oldLocation !== $request->location || 
            $oldStorageType !== $request->storage_type ||
            $oldCondition !== $request->condition || 
            $oldStatus !== $request->status) {
            
            InventoryTransaction::create([
                'inventory_id' => $inventory->id,
                'type' => 'Kiem ke',
                'quantity' => 1,
                'from_location' => $oldLocation,
                'to_location' => $request->location,
                'condition_before' => $oldCondition,
                'condition_after' => $request->condition,
                'status_before' => $oldStatus,
                'status_after' => $request->status,
                'reason' => 'Cập nhật thông tin',
                'notes' => $request->notes,
                'performed_by' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.inventory.show', $inventory->id)
            ->with('success', 'Thông tin sách đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);

        // Tạo transaction thanh lý
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'Thanh ly',
            'quantity' => 1,
            'from_location' => $inventory->location,
            'condition_before' => $inventory->condition,
            'status_before' => $inventory->status,
            'status_after' => 'Thanh ly',
            'reason' => 'Xóa khỏi hệ thống',
            'performed_by' => Auth::id(),
        ]);

        $inventory->delete();

        return back()->with('success', 'Sách đã được xóa khỏi kho thành công!');
    }

    public function transfer(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'to_location' => 'required|string|max:100',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldLocation = $inventory->location;

        $inventory->update([
            'location' => $request->to_location,
        ]);

        // Tạo transaction chuyển kho
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'Chuyen kho',
            'quantity' => 1,
            'from_location' => $oldLocation,
            'to_location' => $request->to_location,
            'condition_before' => $inventory->condition,
            'condition_after' => $inventory->condition,
            'status_before' => $inventory->status,
            'status_after' => $inventory->status,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'performed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Sách đã được chuyển kho thành công!');
    }

    public function repair(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'condition_after' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldCondition = $inventory->condition;

        $inventory->update([
            'condition' => $request->condition_after,
        ]);

        // Tạo transaction sửa chữa
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'Sua chua',
            'quantity' => 1,
            'condition_before' => $oldCondition,
            'condition_after' => $request->condition_after,
            'status_before' => $inventory->status,
            'status_after' => $inventory->status,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'performed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Sách đã được sửa chữa thành công!');
    }

    public function transactions(Request $request)
    {
        $query = InventoryTransaction::with(['inventory.book', 'performer']);

        // Lọc theo loại giao dịch
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Lọc theo khoảng thời gian
        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }

        // Lọc theo người thực hiện
        if ($request->filled('performer_id')) {
            $query->where('performed_by', $request->performer_id);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = \App\Models\User::all();

        return view('admin.inventory.transactions', compact('transactions', 'users'));
    }

    public function dashboard()
    {
        $stats = [
            'total_books' => Inventory::count(),
            'books_in_stock' => Inventory::where('storage_type', 'Kho')->count(),
            'books_on_display' => Inventory::where('storage_type', 'Trung bay')->count(),
            'available_books' => Inventory::where('status', 'Co san')->count(),
            'available_in_stock' => Inventory::where('storage_type', 'Kho')->where('status', 'Co san')->count(),
            'available_on_display' => Inventory::where('storage_type', 'Trung bay')->where('status', 'Co san')->count(),
            'borrowed_books' => Inventory::where('status', 'Dang muon')->count(),
            'borrowed_from_stock' => Inventory::where('storage_type', 'Kho')->where('status', 'Dang muon')->count(),
            'borrowed_from_display' => Inventory::where('storage_type', 'Trung bay')->where('status', 'Dang muon')->count(),
            'damaged_books' => Inventory::where('condition', 'Hong')->count(),
            'lost_books' => Inventory::where('status', 'Mat')->count(),
            'recent_transactions' => InventoryTransaction::with(['inventory.book', 'performer'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'transactions_by_type' => InventoryTransaction::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'books_by_storage_type' => Inventory::selectRaw('storage_type, COUNT(*) as count')
                ->groupBy('storage_type')
                ->get(),
        ];

        return view('admin.inventory.dashboard', compact('stats'));
    }

    public function scanBarcode(Request $request)
    {
        $barcode = $request->get('barcode');
        
        if (!$barcode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã vạch không được để trống'
            ], 400);
        }

        $inventory = Inventory::with(['book', 'creator'])
            ->where('barcode', $barcode)
            ->first();

        if (!$inventory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy sách với mã vạch này'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $inventory
        ]);
    }

    /**
     * Hiển thị form nhập kho
     */
    public function createReceipt()
    {
        $books = Book::all();
        $receiptNumber = InventoryReceipt::generateReceiptNumber();
        $categories = \App\Models\Category::all();
        $publishers = \App\Models\Publisher::all();
        
        // Lấy danh sách vị trí đã sử dụng kèm số lượng sách, phân loại theo storage_type
        $locationsInStock = Inventory::where('storage_type', 'Kho')
            ->select('location', DB::raw('COUNT(*) as book_count'))
            ->groupBy('location')
            ->orderBy('location')
            ->get()
            ->map(function($item) {
                return [
                    'location' => $item->location,
                    'count' => $item->book_count
                ];
            })
            ->toArray();
            
        $locationsOnDisplay = Inventory::where('storage_type', 'Trung bay')
            ->select('location', DB::raw('COUNT(*) as book_count'))
            ->groupBy('location')
            ->orderBy('location')
            ->get()
            ->map(function($item) {
                return [
                    'location' => $item->location,
                    'count' => $item->book_count
                ];
            })
            ->toArray();
        
        return view('admin.inventory.create-receipt', compact(
            'books', 
            'receiptNumber', 
            'categories', 
            'publishers',
            'locationsInStock',
            'locationsOnDisplay'
        ));
    }

    /**
     * Lưu phiếu nhập kho
     */
    public function storeReceipt(Request $request)
    {
        $bookInputType = $request->book_input_type ?? 'existing';
        
        if ($bookInputType === 'new') {
            // Validate cho sách mới
            $request->validate([
                'receipt_date' => 'required|date',
                'ten_sach' => 'required|string|max:255',
                'tac_gia' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'quantity' => 'required|integer|min:1',
                'storage_location' => 'required|string|max:100',
                'storage_type' => 'required|in:Kho,Trung bay',
                'unit_price' => 'nullable|numeric|min:0',
                'supplier' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:500',
                'nha_xuat_ban_id' => 'nullable|exists:publishers,id',
                'nam_xuat_ban' => 'nullable|integer|min:1900|max:' . date('Y'),
                'gia' => 'nullable|numeric|min:0',
                'mo_ta' => 'nullable|string',
            ]);
        } else {
            // Validate cho sách có sẵn
            $request->validate([
                'receipt_date' => 'required|date',
                'book_id' => 'required|exists:books,id',
                'quantity' => 'required|integer|min:1',
                'storage_location' => 'required|string|max:100',
                'storage_type' => 'required|in:Kho,Trung bay',
                'unit_price' => 'nullable|numeric|min:0',
                'supplier' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:500',
            ]);
        }

        DB::beginTransaction();
        try {
            // Nếu là sách mới, tạo Book trước
            if ($bookInputType === 'new') {
                $book = Book::create([
                    'ten_sach' => $request->ten_sach,
                    'tac_gia' => $request->tac_gia,
                    'category_id' => $request->category_id,
                    'nha_xuat_ban_id' => $request->nha_xuat_ban_id,
                    'nam_xuat_ban' => $request->nam_xuat_ban ?? date('Y'),
                    'gia' => $request->gia ?? $request->unit_price ?? 0,
                    'mo_ta' => $request->mo_ta,
                    'trang_thai' => 'active',
                    'danh_gia_trung_binh' => 0,
                    'so_luong_ban' => 0,
                    'so_luot_xem' => 0,
                ]);
                $bookId = $book->id;
            } else {
                $bookId = $request->book_id;
            }

            // Tính tổng giá
            $unitPrice = $request->unit_price ?? 0;
            $totalPrice = $unitPrice * $request->quantity;

            // Tạo số phiếu
            $receiptNumber = InventoryReceipt::generateReceiptNumber();

            // Tạo phiếu nhập
            $receipt = InventoryReceipt::create([
                'receipt_number' => $receiptNumber,
                'receipt_date' => $request->receipt_date,
                'book_id' => $bookId,
                'quantity' => $request->quantity,
                'storage_location' => $request->storage_location,
                'storage_type' => $request->storage_type,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'supplier' => $request->supplier,
                'received_by' => Auth::id(),
                'status' => 'pending', // Có thể cần phê duyệt
                'notes' => $request->notes,
            ]);

            // Tạo các bản copy sách trong kho
            for ($i = 0; $i < $request->quantity; $i++) {
                $baseNumber = Inventory::count() + $i + 1;
                $barcode = 'BK' . str_pad($baseNumber, 6, '0', STR_PAD_LEFT);
                
                // Đảm bảo mã vạch là unique
                $counter = 0;
                while (Inventory::where('barcode', $barcode)->exists() && $counter < 100) {
                    $baseNumber++;
                    $barcode = 'BK' . str_pad($baseNumber, 6, '0', STR_PAD_LEFT);
                    $counter++;
                }
                
                $inventory = Inventory::create([
                    'book_id' => $bookId, // Sử dụng $bookId đã được xác định
                    'barcode' => $barcode,
                    'location' => $request->storage_location,
                    'condition' => 'Moi',
                    'status' => 'Co san',
                    'purchase_price' => $unitPrice,
                    'purchase_date' => $request->receipt_date,
                    'storage_type' => $request->storage_type,
                    'receipt_id' => $receipt->id,
                    'created_by' => Auth::id(),
                ]);

                // Tạo transaction nhập kho
                InventoryTransaction::create([
                    'inventory_id' => $inventory->id,
                    'type' => 'Nhap kho',
                    'quantity' => 1,
                    'to_location' => $request->storage_location,
                    'condition_after' => 'Moi',
                    'status_after' => 'Co san',
                    'reason' => 'Nhập kho theo phiếu: ' . $receipt->receipt_number,
                    'notes' => $request->notes,
                    'performed_by' => Auth::id(),
                ]);
            }


            DB::commit();

            return redirect()->route('admin.inventory.receipts')
                ->with('success', 'Phiếu nhập kho đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách phiếu nhập kho
     */
    public function receipts(Request $request)
    {
        $query = InventoryReceipt::with(['book', 'receiver', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->where('receipt_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('receipt_date', '<=', $request->to_date);
        }

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        $receipts = $query->orderBy('created_at', 'desc')->paginate(20);
        $books = Book::all();

        return view('admin.inventory.receipts', compact('receipts', 'books'));
    }

    /**
     * Chi tiết phiếu nhập kho
     */
    public function showReceipt($id)
    {
        $receipt = InventoryReceipt::with(['book', 'receiver', 'approver', 'inventories'])
            ->findOrFail($id);

        return view('admin.inventory.show-receipt', compact('receipt'));
    }

    /**
     * Phê duyệt phiếu nhập kho
     */
    public function approveReceipt($id)
    {
        $receipt = InventoryReceipt::findOrFail($id);

        if ($receipt->status !== 'pending') {
            return back()->with('error', 'Phiếu nhập này đã được xử lý!');
        }

        DB::beginTransaction();
        try {
            // Cập nhật trạng thái phiếu nhập kho
            $receipt->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
            ]);

            // Tạo các bản copy sách trong kho (chỉ tạo khi chưa có Inventory items cho phiếu này)
            $existingInventories = Inventory::where('receipt_id', $receipt->id)->count();
            
            if ($existingInventories == 0) {
                // Chưa có Inventory items, tạo mới
                for ($i = 0; $i < $receipt->quantity; $i++) {
                    $baseNumber = Inventory::count() + $i + 1;
                    $barcode = 'BK' . str_pad($baseNumber, 6, '0', STR_PAD_LEFT);
                    
                    // Đảm bảo mã vạch là unique
                    $counter = 0;
                    while (Inventory::where('barcode', $barcode)->exists() && $counter < 100) {
                        $baseNumber++;
                        $barcode = 'BK' . str_pad($baseNumber, 6, '0', STR_PAD_LEFT);
                        $counter++;
                    }
                    
                    $inventory = Inventory::create([
                        'book_id' => $receipt->book_id,
                        'barcode' => $barcode,
                        'location' => $receipt->storage_location,
                        'condition' => 'Moi',
                        'status' => 'Co san',
                        'purchase_price' => $receipt->unit_price ?? 0,
                        'purchase_date' => $receipt->receipt_date,
                        'storage_type' => $receipt->storage_type,
                        'receipt_id' => $receipt->id,
                        'created_by' => $receipt->received_by,
                    ]);

                    // Tạo transaction nhập kho
                    InventoryTransaction::create([
                        'inventory_id' => $inventory->id,
                        'type' => 'Nhap kho',
                        'quantity' => 1,
                        'to_location' => $receipt->storage_location,
                        'condition_after' => 'Moi',
                        'status_after' => 'Co san',
                        'reason' => 'Nhập kho theo phiếu: ' . $receipt->receipt_number,
                        'notes' => $receipt->notes,
                        'performed_by' => Auth::id(),
                    ]);
                }
            }

            // Tính tổng số lượng từ các phiếu nhập kho đã được duyệt của sách này
            $totalQuantity = InventoryReceipt::where('book_id', $receipt->book_id)
                ->where('status', 'approved')
                ->sum('quantity');

            // Cập nhật số lượng vào Book
            $book = Book::findOrFail($receipt->book_id);
            $book->update([
                'so_luong' => $totalQuantity
            ]);

            DB::commit();

            return back()->with('success', 'Phiếu nhập kho đã được phê duyệt và số lượng sách đã được cập nhật vào kho!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Approve receipt error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Có lỗi xảy ra khi phê duyệt phiếu nhập kho: ' . $e->getMessage());
        }
    }

    /**
     * Từ chối phiếu nhập kho
     */
    public function rejectReceipt(Request $request, $id)
    {
        $receipt = InventoryReceipt::findOrFail($id);

        if ($receipt->status !== 'pending') {
            return back()->with('error', 'Phiếu nhập này đã được xử lý!');
        }

        $receipt->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'notes' => ($receipt->notes ?? '') . "\nLý do từ chối: " . ($request->reason ?? ''),
        ]);

        return back()->with('success', 'Phiếu nhập kho đã bị từ chối!');
    }

    /**
     * Hiển thị form xuất kho để trưng bày
     */
    public function createDisplayAllocation()
    {
        $books = Book::all();
        return view('admin.inventory.create-display-allocation', compact('books'));
    }

    /**
     * Lưu phân bổ trưng bày
     */
    public function storeDisplayAllocation(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity_on_display' => 'required|integer|min:1',
            'display_area' => 'required|string|max:100',
            'display_start_date' => 'required|date',
            'display_end_date' => 'nullable|date|after:display_start_date',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Kiểm tra số lượng sách có sẵn trong kho
            $availableInStock = Inventory::where('book_id', $request->book_id)
                ->where('storage_type', 'Kho')
                ->where('status', 'Co san')
                ->count();

            if ($availableInStock < $request->quantity_on_display) {
                return back()->withInput()->with('error', 
                    'Số lượng sách trong kho không đủ! Chỉ còn ' . $availableInStock . ' cuốn.');
            }

            // Lấy các sách từ kho để chuyển ra trưng bày
            $inventories = Inventory::where('book_id', $request->book_id)
                ->where('storage_type', 'Kho')
                ->where('status', 'Co san')
                ->limit($request->quantity_on_display)
                ->get();

            // Cập nhật số lượng còn lại trong kho
            $remainingInStock = $availableInStock - $request->quantity_on_display;

            // Tạo phân bổ trưng bày
            $allocation = DisplayAllocation::create([
                'book_id' => $request->book_id,
                'quantity_on_display' => $request->quantity_on_display,
                'quantity_in_stock' => $remainingInStock,
                'display_area' => $request->display_area,
                'display_start_date' => $request->display_start_date,
                'display_end_date' => $request->display_end_date,
                'allocated_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            // Chuyển các sách từ kho sang trưng bày
            foreach ($inventories as $inventory) {
                $oldLocation = $inventory->location;
                
                $inventory->update([
                    'storage_type' => 'Trung bay',
                    'location' => $request->display_area,
                ]);

                // Tạo transaction xuất kho
                InventoryTransaction::create([
                    'inventory_id' => $inventory->id,
                    'type' => 'Xuat kho',
                    'quantity' => 1,
                    'from_location' => $oldLocation,
                    'to_location' => $request->display_area,
                    'condition_before' => $inventory->condition,
                    'condition_after' => $inventory->condition,
                    'status_before' => $inventory->status,
                    'status_after' => $inventory->status,
                    'reason' => 'Xuất kho để trưng bày',
                    'notes' => $request->notes,
                    'performed_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.inventory.display-allocations')
                ->with('success', 'Phân bổ trưng bày đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách phân bổ trưng bày
     */
    public function displayAllocations(Request $request)
    {
        $query = DisplayAllocation::with(['book', 'allocator']);

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        if ($request->filled('display_area')) {
            $query->where('display_area', 'like', "%{$request->display_area}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->where('display_end_date', '<', now()->toDateString());
            }
        }

        $allocations = $query->orderBy('created_at', 'desc')->paginate(20);
        $books = Book::all();

        return view('admin.inventory.display-allocations', compact('allocations', 'books'));
    }

    /**
     * Thu hồi sách từ trưng bày về kho
     */
    public function returnFromDisplay(Request $request, $id)
    {
        $allocation = DisplayAllocation::findOrFail($id);

        $request->validate([
            'return_location' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Lấy các sách đang trưng bày
            $inventories = Inventory::where('book_id', $allocation->book_id)
                ->where('storage_type', 'Trung bay')
                ->where('location', $allocation->display_area)
                ->where('status', 'Co san')
                ->limit($allocation->quantity_on_display)
                ->get();

            // Chuyển các sách về kho
            foreach ($inventories as $inventory) {
                $oldLocation = $inventory->location;

                $inventory->update([
                    'storage_type' => 'Kho',
                    'location' => $request->return_location,
                ]);

                // Tạo transaction nhập lại kho
                InventoryTransaction::create([
                    'inventory_id' => $inventory->id,
                    'type' => 'Nhap kho',
                    'quantity' => 1,
                    'from_location' => $oldLocation,
                    'to_location' => $request->return_location,
                    'condition_before' => $inventory->condition,
                    'condition_after' => $inventory->condition,
                    'status_before' => $inventory->status,
                    'status_after' => $inventory->status,
                    'reason' => 'Thu hồi từ trưng bày về kho',
                    'notes' => $request->notes,
                    'performed_by' => Auth::id(),
                ]);
            }

            // Cập nhật phân bổ
            $allocation->update([
                'quantity_on_display' => 0,
                'quantity_in_stock' => Inventory::where('book_id', $allocation->book_id)
                    ->where('storage_type', 'Kho')
                    ->where('status', 'Co san')
                    ->count(),
                'display_end_date' => now()->toDateString(),
            ]);

            DB::commit();

            return back()->with('success', 'Sách đã được thu hồi về kho thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Báo cáo tổng hợp kho
     */
    public function report()
    {
        // Thống kê tổng quan kho
        $totalBooksInStock = Inventory::inStock()->count();
        $availableInStock = Inventory::inStock()->where('status', 'Co san')->count();
        $borrowedFromStock = Inventory::inStock()->where('status', 'Dang muon')->count();
        $remainingInStock = $totalBooksInStock - $borrowedFromStock;
        
        // Thống kê nhập kho
        $totalImported = InventoryReceipt::where('storage_type', 'Kho')
            ->where('status', 'approved')
            ->sum('quantity');
        $totalImportedReceipts = InventoryReceipt::where('storage_type', 'Kho')
            ->where('status', 'approved')
            ->count();
        
        // Danh sách phiếu nhập kho
        $importReceipts = InventoryReceipt::with(['book', 'receiver', 'approver'])
            ->where('storage_type', 'Kho')
            ->where('status', 'approved')
            ->orderBy('receipt_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Thống kê theo từng sách - Đồng bộ từ cả quản lý sách và kho
        // Lấy tất cả sách từ bảng books (quản lý sách)
        $allBooks = Book::orderBy('ten_sach')->get()->keyBy('id');
        
        // Lấy thống kê từ inventories (kho) theo từng book_id
        $inventoryStats = Inventory::inStock()
            ->select('book_id', DB::raw('count(*) as total_count'))
            ->selectRaw('sum(case when status = "Co san" then 1 else 0 end) as available_count')
            ->selectRaw('sum(case when status = "Dang muon" then 1 else 0 end) as borrowed_count')
            ->groupBy('book_id')
            ->get()
            ->keyBy('book_id');
        
        // Đồng bộ hóa: kết hợp dữ liệu từ books (quản lý sách) và inventories (kho)
        // Bước 1: Xử lý sách từ quản lý sách (books)
        $booksInStock = $allBooks->map(function($book) use ($inventoryStats) {
            $stats = $inventoryStats->get($book->id);
            
            return [
                'book' => $book,
                'total' => $stats ? (int)$stats->total_count : 0,
                'available' => $stats ? (int)$stats->available_count : 0,
                'borrowed' => $stats ? (int)$stats->borrowed_count : 0,
                'remaining' => $stats ? (int)($stats->total_count - $stats->borrowed_count) : 0,
            ];
        });
        
        // Bước 2: Xử lý sách có trong kho nhưng không có trong quản lý sách (lỗi dữ liệu)
        $orphanedInventoryStats = $inventoryStats->filter(function($stat) use ($allBooks) {
            return !$allBooks->has($stat->book_id);
        });
        
        // Thêm các sách orphaned vào danh sách
        foreach ($orphanedInventoryStats as $stat) {
            $book = Book::find($stat->book_id);
            if ($book) {
                // Nếu tìm thấy book, thêm vào danh sách
                $booksInStock->put($book->id, [
                    'book' => $book,
                    'total' => (int)$stat->total_count,
                    'available' => (int)$stat->available_count,
                    'borrowed' => (int)$stat->borrowed_count,
                    'remaining' => (int)($stat->total_count - $stat->borrowed_count),
                ]);
            }
        }
        
        // Lọc và sắp xếp: chỉ hiển thị sách có trong kho (total > 0) và sắp xếp theo tên
        $booksInStock = $booksInStock
            ->filter(function($item) {
                return $item['total'] > 0;
            })
            ->sortBy(function($item) {
                return $item['book']->ten_sach ?? '';
            })
            ->values();
        
        // Danh sách ai đang mượn sách từ kho
        $currentBorrows = Borrow::with(['reader', 'book', 'librarian'])
            ->whereHas('book.inventories', function($query) {
                $query->where('storage_type', 'Kho')
                      ->where('status', 'Dang muon');
            })
            ->where('trang_thai', 'Dang muon')
            ->orderBy('ngay_muon', 'desc')
            ->get();
        
        // Danh sách ai đã trả sách (gần đây)
        $returnedBorrows = Borrow::with(['reader', 'book', 'librarian'])
            ->whereHas('book.inventories', function($query) {
                $query->where('storage_type', 'Kho');
            })
            ->where('trang_thai', 'Da tra')
            ->orderBy('ngay_tra_thuc_te', 'desc')
            ->limit(100)
            ->get();
        
        // Thống kê mượn/trả theo thời gian
        $borrowStats = [
            'today' => Borrow::whereHas('book.inventories', function($query) {
                    $query->where('storage_type', 'Kho');
                })
                ->whereDate('ngay_muon', today())
                ->count(),
            'this_month' => Borrow::whereHas('book.inventories', function($query) {
                    $query->where('storage_type', 'Kho');
                })
                ->whereMonth('ngay_muon', now()->month)
                ->whereYear('ngay_muon', now()->year)
                ->count(),
            'returned_today' => Borrow::whereHas('book.inventories', function($query) {
                    $query->where('storage_type', 'Kho');
                })
                ->where('trang_thai', 'Da tra')
                ->whereDate('ngay_tra_thuc_te', today())
                ->count(),
            'returned_this_month' => Borrow::whereHas('book.inventories', function($query) {
                    $query->where('storage_type', 'Kho');
                })
                ->where('trang_thai', 'Da tra')
                ->whereMonth('ngay_tra_thuc_te', now()->month)
                ->whereYear('ngay_tra_thuc_te', now()->year)
                ->count(),
        ];
        
        $stats = [
            // Tổng quan
            'total_books_in_stock' => $totalBooksInStock,
            'available_in_stock' => $availableInStock,
            'borrowed_from_stock' => $borrowedFromStock,
            'remaining_in_stock' => $remainingInStock,
            
            // Nhập kho
            'total_imported' => $totalImported,
            'total_imported_receipts' => $totalImportedReceipts,
            'import_receipts' => $importReceipts,
            
            // Chi tiết theo sách
            'books_in_stock' => $booksInStock,
            
            // Ai mượn
            'current_borrows' => $currentBorrows,
            
            // Ai trả
            'returned_borrows' => $returnedBorrows,
            
            // Thống kê mượn/trả
            'borrow_stats' => $borrowStats,
        ];

        return view('admin.inventory.report', compact('stats'));
    }

    /**
     * Đồng bộ hóa tất cả sản phẩm từ kho lên trang chủ
     * Đảm bảo tất cả sách trong kho đều có trang_thai = 'active' để hiển thị trên trang chủ
     */
    public function syncToHomepage(Request $request)
    {
        try {
            DB::beginTransaction();

            // Lấy tất cả các book_id từ inventories (cả kho và trưng bày)
            $bookIds = Inventory::select('book_id')
                ->distinct()
                ->pluck('book_id')
                ->toArray();

            // Đếm số lượng sách cần đồng bộ
            $totalBooks = count($bookIds);
            $updatedCount = 0;
            $alreadyActiveCount = 0;

            // Cập nhật tất cả sách trong kho để có trang_thai = 'active'
            foreach ($bookIds as $bookId) {
                $book = Book::find($bookId);
                if ($book) {
                    // Chỉ cập nhật nếu sách chưa active
                    if ($book->trang_thai !== 'active') {
                        $book->update(['trang_thai' => 'active']);
                        $updatedCount++;
                    } else {
                        $alreadyActiveCount++;
                    }
                }
            }

            DB::commit();

            $message = "Đồng bộ hóa thành công! ";
            $message .= "Tổng số sách trong kho: {$totalBooks}. ";
            $message .= "Đã cập nhật: {$updatedCount} sách. ";
            $message .= "Đã active sẵn: {$alreadyActiveCount} sách.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'total_books' => $totalBooks,
                        'updated' => $updatedCount,
                        'already_active' => $alreadyActiveCount,
                    ]
                ]);
            }

            return redirect()->route('admin.inventory.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Có lỗi xảy ra khi đồng bộ hóa: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Import tất cả sách từ quản lý sách vào kho
     * Tạo inventory record cho tất cả sách chưa có trong kho
     */
    public function importAllBooks(Request $request)
    {
        try {
            DB::beginTransaction();

            // Lấy tất cả book_id đã có trong inventory
            $existingBookIds = Inventory::select('book_id')
                ->distinct()
                ->pluck('book_id')
                ->toArray();

            // Lấy tất cả sách chưa có trong kho
            $booksToImport = Book::whereNotIn('id', $existingBookIds)
                ->get();

            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($booksToImport as $book) {
                try {
                    // Tạo mã vạch tự động
                    $baseNumber = Inventory::count() + $imported + 1;
                    $barcode = 'BK' . str_pad($baseNumber, 6, '0', STR_PAD_LEFT);
                    
                    // Đảm bảo mã vạch là unique
                    $counter = 0;
                    while (Inventory::where('barcode', $barcode)->exists() && $counter < 100) {
                        $baseNumber++;
                        $barcode = 'BK' . str_pad($baseNumber, 6, '0', STR_PAD_LEFT);
                        $counter++;
                    }

                    // Tạo inventory record
                    $inventory = Inventory::create([
                        'book_id' => $book->id,
                        'barcode' => $barcode,
                        'location' => 'Kho chính',
                        'storage_type' => 'Kho',
                        'condition' => 'Moi',
                        'status' => 'Co san',
                        'purchase_price' => $book->gia ?? null,
                        'purchase_date' => now(),
                        'created_by' => Auth::id(),
                        'notes' => 'Tự động import từ quản lý sách',
                    ]);

                    // Tạo transaction nhập kho
                    InventoryTransaction::create([
                        'inventory_id' => $inventory->id,
                        'type' => 'Nhap kho',
                        'quantity' => 1,
                        'to_location' => 'Kho chính',
                        'condition_after' => 'Moi',
                        'status_after' => 'Co san',
                        'reason' => 'Tự động import tất cả sách từ quản lý sách',
                        'notes' => 'Import tự động cho sách: ' . $book->ten_sach,
                        'performed_by' => Auth::id(),
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Sách '{$book->ten_sach}' (ID: {$book->id}): " . $e->getMessage();
                }
            }

            // Đếm số sách đã có trong kho (bỏ qua)
            $skipped = count($existingBookIds);

            DB::commit();

            $message = "Đã import thành công {$imported} sách vào kho! ";
            if ($skipped > 0) {
                $message .= "Đã bỏ qua {$skipped} sách đã có trong kho. ";
            }
            if (!empty($errors)) {
                $message .= "Có " . count($errors) . " lỗi.";
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'imported' => $imported,
                        'skipped' => $skipped,
                        'errors' => $errors,
                    ]
                ]);
            }

            return redirect()->route('admin.inventory.index')
                ->with('success', $message)
                ->with('import_errors', $errors);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Có lỗi xảy ra khi import sách: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Export inventory to Excel
     */
    public function export(Request $request)
    {
        $filename = 'inventory_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        
        return Excel::download(new InventoryExport($request), $filename);
    }

    /**
     * Import inventory from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $file = $request->file('file');
            
            // Đọc file Excel
            $data = Excel::toArray([], $file);
            
            if (empty($data) || empty($data[0])) {
                return back()->with('error', 'File không có dữ liệu!');
            }

            $rows = $data[0];
            $header = array_shift($rows); // Bỏ dòng header
            
            $imported = 0;
            $errors = [];

            DB::beginTransaction();
            
            foreach ($rows as $index => $row) {
                try {
                    // Giả sử format: book_id, barcode, location, condition, status, purchase_price, purchase_date
                    if (count($row) < 4) continue;

                    $bookId = $row[0] ?? null;
                    $barcode = $row[1] ?? null;
                    $location = $row[2] ?? 'Kho chính';
                    $condition = $row[3] ?? 'Moi';
                    $status = $row[4] ?? 'Co san';
                    $purchasePrice = $row[5] ?? null;
                    $purchaseDate = $row[6] ?? now();

                    if (!$bookId || !Book::find($bookId)) {
                        $errors[] = "Dòng " . ($index + 2) . ": Không tìm thấy sách với ID {$bookId}";
                        continue;
                    }

                    // Tạo mã vạch tự động nếu không có
                    if (!$barcode) {
                        $barcode = 'BK' . str_pad(Inventory::count() + 1, 6, '0', STR_PAD_LEFT);
                    }

                    // Kiểm tra mã vạch đã tồn tại
                    if (Inventory::where('barcode', $barcode)->exists()) {
                        $errors[] = "Dòng " . ($index + 2) . ": Mã vạch {$barcode} đã tồn tại";
                        continue;
                    }

                    $inventory = Inventory::create([
                        'book_id' => $bookId,
                        'barcode' => $barcode,
                        'location' => $location,
                        'condition' => $condition,
                        'status' => $status,
                        'purchase_price' => $purchasePrice,
                        'purchase_date' => $purchaseDate,
                        'storage_type' => 'Kho',
                        'created_by' => Auth::id(),
                    ]);

                    // Tạo transaction nhập kho
                    InventoryTransaction::create([
                        'inventory_id' => $inventory->id,
                        'type' => 'Nhap kho',
                        'quantity' => 1,
                        'to_location' => $location,
                        'condition_after' => $condition,
                        'status_after' => $status,
                        'reason' => 'Nhập kho từ file Excel',
                        'performed_by' => Auth::id(),
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "Đã nhập thành công {$imported} sách vào kho!";
            if (!empty($errors)) {
                $message .= " Có " . count($errors) . " lỗi.";
                return back()->with('warning', $message)->with('import_errors', $errors);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi nhập file: ' . $e->getMessage());
        }
    }
}