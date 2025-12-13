<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Reader;
use App\Models\Book;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Inventory;
use App\Models\BorrowItem;
use App\Models\BorrowPayment;
use App\Models\ShippingLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /* ============================================================
        1) DANH SÁCH PHIẾU MƯỢN + LỌC + TÌM KIẾM
    ============================================================ */
    public function index(Request $request)
    {
        // Sử dụng fresh() để đảm bảo load dữ liệu mới nhất từ database
        $query = Borrow::with(['reader', 'librarian', 'items', 'voucher']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('reader', function($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai === 'Cho duyet') {
                $query->whereHas('items', function($q) {
                    $q->where('trang_thai', 'Cho duyet');
                });
            } else {
                $query->where('trang_thai', $request->trang_thai);
            }
        }

        if (!$request->filled('trang_thai')) {
            $query->orderByRaw("EXISTS (
                SELECT 1 FROM borrow_items 
                WHERE borrow_items.borrow_id = borrows.id 
                AND borrow_items.trang_thai = 'Cho duyet'
            ) DESC")
            ->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $borrows = $query->paginate(10);
        
        // Đảm bảo refresh lại items cho mỗi borrow để có dữ liệu mới nhất từ database
        $borrows->getCollection()->transform(function($borrow) {
            // Reload hoàn toàn từ database để tránh cache
            $borrow = Borrow::with(['reader', 'librarian', 'items', 'voucher'])->find($borrow->id);
            return $borrow;
        });
        
        return response()
            ->view('admin.borrows.index', compact('borrows'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /* ============================================================
        2) TẠO PHIẾU MƯỢN
    ============================================================ */
    public function create()
    {
        $readers = Reader::where('trang_thai', 'Hoat dong')->get();
        $books = Book::all();
        $librarians = User::where('role', 'admin')->get();

        return view('admin.borrows.create', compact('readers', 'books', 'librarians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reader_id' => 'nullable|exists:readers,id',
            'librarian_id' => 'required|exists:users,id',
            'ten_nguoi_muon' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'tinh_thanh' => 'required|string|max:255',
            'huyen' => 'required|string|max:255',
            'xa' => 'required|string|max:255',
            'so_nha' => 'required|string|max:255',
            'ngay_muon' => 'required|date',
            'ghi_chu' => 'nullable|string|max:500',
        ]);

        Borrow::create($request->all());

        return redirect()->route('admin.borrows.index')
            ->with('success', 'Cho mượn sách thành công!');
    }


    /* ============================================================
        3) CHỈNH SỬA PHIẾU MƯỢN
    ============================================================ */
    public function edit($id)
    {
        $borrow = Borrow::with([
            'items.book.category', 
            'items.inventory', 
            'reader', 
            'librarian',
            'voucher'
        ])->findOrFail($id);
        
        $readers = Reader::where('trang_thai', 'Hoat dong')->get();
        $books = Book::all();
        $librarians = User::where('role','admin')->get();

        // Lấy danh sách voucher khả dụng
        $vouchers = Voucher::where('kich_hoat', 1)
            ->where('trang_thai', 'active')
            ->whereDate('ngay_bat_dau', '<=', now())
            ->whereDate('ngay_ket_thuc', '>=', now())
            ->where('so_luong', '>', 0);
        
        // Nếu có reader_id thì lọc theo reader
        if ($borrow->reader_id) {
            $vouchers = $vouchers->where(function($query) use ($borrow) {
                $query->where('reader_id', $borrow->reader_id)
                      ->orWhereNull('reader_id');
            });
        }
        
        $vouchers = $vouchers->get();

        return view('admin.borrows.edit', compact(
            'borrow', 'readers', 'books', 'vouchers', 'librarians'
        ));
    }

    public function update(Request $request, $id)
    {
        $borrow = Borrow::findOrFail($id);

        $data = $request->validate([
            'reader_id' => 'nullable|exists:readers,id',
            'librarian_id' => 'nullable|exists:users,id',
            'ten_nguoi_muon' => 'required|string|max:255',
            'ngay_muon' => 'required|date',
            'so_dien_thoai' => 'required|string|max:20',
            'tinh_thanh' => 'required|string|max:255',
            'huyen' => 'required|string|max:255',
            'xa' => 'required|string|max:255',
            'so_nha' => 'required|string|max:255',
            'ghi_chu' => 'nullable|string',
            'voucher_id' => 'nullable|exists:vouchers,id',
        ]);

        $borrow->update($data);

        // Cập nhật lại tổng tiền
        $borrow->recalculateTotals();

        return redirect()->route('admin.borrows.index')
            ->with('success', 'Cập nhật phiếu mượn thành công!');
    }

    /* ============================================================
        4) XOÁ PHIẾU MƯỢN
    ============================================================ */
    public function destroy($id)
    {
        Borrow::destroy($id);
        return redirect()->route('admin.borrows.index')
            ->with('success', 'Xóa phiếu mượn thành công!');
    }

    /* ============================================================
        5) CHI TIẾT PHIẾU MƯỢN
    ============================================================ */
    public function show($id)
    {
        $borrow = Borrow::with(['reader','librarian','items.book'])
            ->findOrFail($id);

        return view('admin.borrows.show', [
            'borrow' => $borrow,
            'borrowItems' => $borrow->items
        ]);
    }

    /* ============================================================
        6) TẠO MÓN MƯỢN (ITEM)
    ============================================================ */
    public function createItem($borrowId)
    {
        $borrow = Borrow::findOrFail($borrowId);
        $books = Book::all();
        $librarians = User::where('role','admin')->get();

        return view('admin.borrows.createitem', compact('borrow','books','librarians'));
    }

    public function storeItem(Request $request, $borrowId)
    {
        $request->validate([
            'book_id'        => 'required|exists:books,id',
            'inventory_ids'  => 'required|array',
            'inventory_ids.*' => 'exists:inventories,id',
            'tien_coc'       => 'nullable|numeric|min:0',
            'tien_thue'      => 'nullable|numeric|min:0',
            'tien_ship'      => 'nullable|numeric|min:0',
            'trang_thai_coc' => 'required|in:cho_xu_ly,da_thu,da_hoan,tru_vao_phat',
            'ngay_muon'      => 'required|date',
            'ngay_hen_tra'   => 'required|date|after_or_equal:ngay_muon',
            'ghi_chu'        => 'nullable|string',
            'librarian_id'   => 'nullable|exists:users,id',
        ]);

        $borrow = Borrow::findOrFail($borrowId);
        $book   = Book::findOrFail($request->book_id);
        $reader = $borrow->reader;
        $hasCard = $reader ? true : false;

        foreach ($request->inventory_ids as $invId) {
            $inventory = Inventory::findOrFail($invId);
            
            // Tính phí thuê và tiền cọc dựa trên giá sách và số ngày mượn
            $fees = \App\Services\PricingService::calculateFees(
                $book,
                $inventory,
                $request->ngay_muon,
                $request->ngay_hen_tra,
                $hasCard
            );
            
            $borrow->items()->create([
                'book_id'        => $request->book_id,
                'inventorie_id'  => $invId,
                'tien_coc'       => $request->tien_coc ?? $fees['tien_coc'],
                'tien_thue'      => $request->tien_thue ?? $fees['tien_thue'],
                'tien_ship'      => $request->tien_ship ?? 0,
                'ngay_muon'      => $request->ngay_muon,
                'ngay_hen_tra'   => $request->ngay_hen_tra,
                'ghi_chu'        => $request->ghi_chu,
                'librarian_id'   => $request->librarian_id ?? auth()->id(),
            ]);
        }

        // cập nhật tổng tiền
        $totals = $borrow->items()
            ->selectRaw('
            SUM(COALESCE(tien_coc,0)) as coc,
            SUM(COALESCE(tien_thue,0)) as thue,
            SUM(COALESCE(tien_ship,0)) as ship
        ')->first();

        $borrow->update([
            'tien_coc' => $totals->coc,
            'tien_thue' => $totals->thue,
            'tien_ship' => $totals->ship,
            'tong_tien' => $totals->coc + $totals->thue + $totals->ship,
        ]);

        return back()->with('success','Đã thêm sách vào phiếu mượn!');
    }

    /* ============================================================
        7) CẬP NHẬT TRẠNG THÁI ITEM
    ============================================================ */
    public function returnItem($id)
    {
        $item = BorrowItem::findOrFail($id);

        $item->update([
            'trang_thai' => 'Da tra',
            'ngay_tra_thuc_te' => now()->toDateString(),
        ]);

        return back()->with('success','Đã trả sách!');
    }

    /* ============================================================
        8) XỬ LÝ PHIẾU MƯỢN (TẠO PAYMENT + SHIPPING)
    ============================================================ */
    public function processBorrow($id)
    {
        $borrow = Borrow::with('items')->findOrFail($id);

        // Kiểm tra xem có items nào đang ở trạng thái "Chua nhan" không
        $chuaNhanItems = $borrow->items->where('trang_thai', 'Chua nhan');
        
        if ($chuaNhanItems->isEmpty()) {
            return back()->with('error', 'Không có sách nào đang ở trạng thái "Chưa nhận" để xử lý!');
        }

        // Cập nhật trạng thái tất cả items từ "Chua nhan" sang "Dang muon"
        foreach ($chuaNhanItems as $item) {
            $item->update([
                'trang_thai' => 'Dang muon',
                'ngay_muon' => $item->ngay_muon ?? now(),
            ]);
        }

        BorrowPayment::create([
            'borrow_id' => $borrow->id,
            'amount'    => $borrow->tong_tien ?? 0,
            'method'    => 'cash',
            'status'    => 'pending',
        ]);

        ShippingLog::create([
            'borrow_id' => $borrow->id,
            'status'    => 'cho_xu_ly',
            'shipper_note' => 'Phiếu mượn đã xác nhận.',
        ]);

        $borrow->update([
            'trang_thai' => 'Dang muon'
        ]);

        return back()->with('success','Đã xử lý phiếu mượn thành công! Các sách đã chuyển sang trạng thái "Đang mượn".');
    }

    /* ============================================================
        9) DUYỆT PHIẾU MƯỢN (CHUYỂN TRẠNG THÁI TỪ "CHỜ DUYỆT" SANG "ĐANG MƯỢN")
    ============================================================ */
    public function approve($id)
    {
        $borrow = Borrow::with('items')->findOrFail($id);

        // Kiểm tra xem có items nào đang ở trạng thái "Cho duyet" không
        $pendingItems = $borrow->items->where('trang_thai', 'Cho duyet');
        
        if ($pendingItems->isEmpty()) {
            return back()->with('error', 'Không có sách nào đang chờ duyệt trong phiếu mượn này!');
        }

        // Cập nhật trạng thái tất cả items từ "Cho duyet" sang "Dang muon"
        foreach ($pendingItems as $item) {
            $item->update([
                'trang_thai' => 'Dang muon',
                'ngay_muon' => $item->ngay_muon ?? now(),
            ]);
        }

        // Tạo payment và shipping log
        BorrowPayment::create([
            'borrow_id' => $borrow->id,
            'amount'    => $borrow->tong_tien ?? 0,
            'method'    => 'cash',
            'status'    => 'pending',
        ]);

        ShippingLog::create([
            'borrow_id' => $borrow->id,
            'status'    => 'cho_xu_ly',
            'shipper_note' => 'Phiếu mượn đã được duyệt và chuyển sang đang mượn.',
        ]);

        // Cập nhật trạng thái của borrow sang "Dang muon"
        $borrow->update([
            'trang_thai' => 'Dang muon'
        ]);

        return back()->with('success', 'Đã duyệt phiếu mượn thành công! Các sách đã chuyển sang trạng thái "Đang mượn".');
    }

    /* ============================================================
        10) CLIENT – LỊCH SỬ MƯỢN
    ============================================================ */
public function clientIndex()
{
$reader = Reader::where('user_id', Auth::id())->first();

if (!$reader) {
    return view('borrow.index', ['borrow_items' => collect(), 'total' => 0]);
}

$borrow_items = BorrowItem::with(['book', 'borrow'])
    ->whereHas('borrow', fn($q) => $q->where('reader_id', $reader->id))
    ->orderBy('created_at', 'DESC')
    ->paginate(10);

// Tính tổng tiền cọc tất cả phiếu mượn của reader
$total = $borrow_items->sum(fn($item) => $item->borrow->tong_tien ?? 0);

return view('borrow.index', compact('borrow_items', 'total'));

}



public function clientShow($id)
{
    $readerId = Auth::id();

    $borrow = Borrow::where('reader_id', $readerId)
        ->with(['items.book'])
        ->findOrFail($id);

    return view('borrow.show', compact('borrow'));
}

public function clientCancel($id)
{
    $readerId = Auth::id();

    $borrow = Borrow::where('reader_id', $readerId)->findOrFail($id);

    // Các trạng thái cho phép hủy
    if (!in_array($borrow->trang_thai, ['Cho duyet', 'Dang muon'])) {
        return back()->with('error', 'Không thể hủy đơn này.');
    }

    $borrow->trang_thai = 'Huy';
    $borrow->save();

    return back()->with('success', 'Đơn đã được hủy.');
}

}
