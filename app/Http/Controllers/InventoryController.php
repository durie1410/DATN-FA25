<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $inventories = $query->orderBy('created_at', 'desc')->paginate(20);
        $books = Book::all();

        return view('admin.inventory.index', compact('inventories', 'books'));
    }

    public function create()
    {
        $books = Book::all();
        return view('admin.inventory.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'barcode' => 'nullable|string|max:100|unique:inventories',
            'location' => 'required|string|max:100',
            'condition' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
            'status' => 'required|in:Co san,Dang muon,Mat,Hong,Thanh ly',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Tạo mã vạch tự động nếu không có
        $barcode = $request->barcode;
        if (!$barcode) {
            $barcode = 'BK' . str_pad(Inventory::count() + 1, 6, '0', STR_PAD_LEFT);
        }

        $inventory = Inventory::create([
            'book_id' => $request->book_id,
            'barcode' => $barcode,
            'location' => $request->location,
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
            'reason' => 'Nhập kho mới',
            'notes' => $request->notes,
            'performed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Sách đã được thêm vào kho thành công!');
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
            'condition' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
            'status' => 'required|in:Co san,Dang muon,Mat,Hong,Thanh ly',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldLocation = $inventory->location;
        $oldCondition = $inventory->condition;
        $oldStatus = $inventory->status;

        $inventory->update([
            'location' => $request->location,
            'condition' => $request->condition,
            'status' => $request->status,
            'purchase_price' => $request->purchase_price,
            'purchase_date' => $request->purchase_date,
            'notes' => $request->notes,
        ]);

        // Tạo transaction nếu có thay đổi
        if ($oldLocation !== $request->location || 
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
            'available_books' => Inventory::where('status', 'Co san')->count(),
            'borrowed_books' => Inventory::where('status', 'Dang muon')->count(),
            'damaged_books' => Inventory::where('condition', 'Hong')->count(),
            'lost_books' => Inventory::where('status', 'Mat')->count(),
            'recent_transactions' => InventoryTransaction::with(['inventory.book', 'performer'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'transactions_by_type' => InventoryTransaction::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
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
}