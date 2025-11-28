<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Reader;
use App\Models\Book;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Inventory;
use App\Models\Librarian; 
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrow::with(['reader', 'librarian', 'items', 'voucher']);

        // Tìm kiếm theo tên tác giả hoặc tên sách
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('reader', function($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái của BorrowItem
        if ($request->filled('trang_thai')) {
            if ($request->trang_thai === 'Cho duyet') {
                // Lọc các Borrow có ít nhất 1 BorrowItem với trạng thái "Cho duyet"
                $query->whereHas('items', function($q) {
                    $q->where('trang_thai', 'Cho duyet');
                });
            } else {
                $query->where('trang_thai', $request->trang_thai);
            }
        }

        // Mặc định ưu tiên hiển thị các Borrow có BorrowItem "Cho duyet" trước
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

        return view('admin.borrows.index', compact('borrows'));
    }

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
    'ngay_muon' => 'required|date',
    'huyen' => 'required|string|max:255',
    'xa' => 'required|string|max:255',
    'so_nha' => 'required|string|max:255',
    'ghi_chu' => 'nullable|string',
], [
    'librarian_id.exists' => 'Thủ thư không tồn tại.',
    'ten_nguoi_muon.required' => 'Tên người mượn là bắt buộc.',
    'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
    'tinh_thanh.required' => 'Tỉnh/Thành là bắt buộc.',
    'ngay_muon.required' => 'Ngày mượn là bắt buộc.',
    'ngay_muon.date' => 'Ngày mượn không đúng định dạng.',
    'huyen.required' => 'Huyện/Quận là bắt buộc.',
    'xa.required' => 'Xã/Phường là bắt buộc.',
    'so_nha.required' => 'Số nhà là bắt buộc.',
    'librarian_id.required' => 'Thủ thư là bắt buộc.',

    'ghi_chu.max' => 'Ghi chú không được vượt quá 500 ký tự.',
]);


        // Kiểm tra sách đã được mượn chưa
      

        Borrow::create($request->all());

        return redirect()->route('admin.borrows.index')->with('success', 'Cho mượn sách thành công!');
    }

    public function edit($id)
    {
        $borrow = Borrow::findOrFail($id);
        $readers = Reader::where('trang_thai', 'Hoat dong')->get();
        $books = Book::all();
       $vouchers = Voucher::where('reader_id', $borrow->reader_id)
        ->where('kich_hoat', 1)
        ->where('trang_thai', 'active')
        ->whereDate('ngay_bat_dau', '<=', now())
        ->whereDate('ngay_ket_thuc', '>=', now())
        ->where('so_luong', '>', 0)
        ->get();
        $librarians = User::where('role', 'admin')->get();

        return view('admin.borrows.edit', compact('borrow', 'readers', 'books', 'vouchers', 'librarians'));
    }

public function update(Request $request, $id)
{
    // 1️⃣ Lấy phiếu mượn
    $borrow = Borrow::findOrFail($id);

    // 2️⃣ Validate dữ liệu từ form
    $data = $request->validate([
        'reader_id'      => 'nullable|exists:readers,id',
        'librarian_id'   => 'nullable|exists:librarians,id',
        'ten_nguoi_muon' => 'required|string|max:255',
        'ngay_muon'      => 'required|date',
        'so_dien_thoai'  => 'required|string|max:20',
        'tinh_thanh'     => 'required|string|max:255',
        'huyen'          => 'required|string|max:255',
        'xa'             => 'required|string|max:255',
        'so_nha'         => 'required|string|max:255',
        'trang_thai'     => 'required|string|in:Dang muon,Da tra,Qua han,Mat sach',
        'ghi_chu'        => 'nullable|string',
        'voucher_id'     => 'nullable|exists:vouchers,id',
        'tong_tien'      => 'required|numeric|min:0',
    ]);

    // 3️⃣ Cập nhật thông tin phiếu mượn
    $borrow->reader_id     = $data['reader_id'] ?? null;
    $borrow->ten_nguoi_muon = $data['ten_nguoi_muon'];
    $borrow->librarian_id  = $data['librarian_id'] ?? null;
    $borrow->ngay_muon     = $data['ngay_muon'];
    $borrow->so_dien_thoai = $data['so_dien_thoai'];
    $borrow->tinh_thanh    = $data['tinh_thanh'];
    $borrow->huyen         = $data['huyen'];
    $borrow->xa            = $data['xa'];
    $borrow->so_nha        = $data['so_nha'];
    $borrow->trang_thai    = $data['trang_thai'];
    $borrow->ghi_chu       = $data['ghi_chu'] ?? null;
    $borrow->voucher_id    = $data['voucher_id'] ?? null;

    // 4️⃣ Tính tổng tiền cọc + ship
    $totalCoc  = $borrow->items->sum('tien_coc');
    $totalShip = $borrow->items->sum('tien_ship');
    $discount  = 0;

 if ($borrow->voucher_id) {
    $voucher = Voucher::find($borrow->voucher_id);
    if ($voucher && $totalShip >= $voucher->don_toi_thieu) {
        $discount = $voucher->loai === 'percentage' 
                    ? $totalShip * ($voucher->gia_tri / 100) // chỉ giảm ship
                    : min($totalShip, $voucher->gia_tri);    // chỉ giảm ship nếu cố định
    }
}

$borrow->tong_tien = max(0, $totalCoc + $totalShip - ($discount ?? 0));



    // 5️⃣ Lưu phiếu mượn
    $borrow->save();

    return redirect()->route('admin.borrows.index')->with('success', 'Cập nhật phiếu mượn thành công!');
}




    public function destroy($id)
    {
        Borrow::destroy($id);
        return redirect()->route('admin.borrows.index')->with('success', 'Xóa phiếu mượn thành công!');
    }

    public function return($id)
    {
        $borrow = Borrow::findOrFail($id);
        
        if (!$borrow->canReturn()) {
            return back()->withErrors(['error' => 'Không thể trả sách này.']);
        }
        
        $borrow->update([
            'trang_thai' => 'Da tra',
            'ngay_tra_thuc_te' => now()->toDateString(),
        ]);

        return redirect()->route('admin.borrows.index')->with('success', 'Trả sách thành công!');
    }

    public function extend($id)
    {
        $borrow = Borrow::findOrFail($id);
        
        if (!$borrow->canExtend()) {
            return back()->withErrors(['error' => 'Không thể gia hạn mượn sách này.']);
        }
        
        $days = request('days', 7); // Mặc định gia hạn 7 ngày
        
        if ($borrow->extend($days)) {
            return redirect()->route('admin.borrows.index')->with('success', "Gia hạn thành công! Hạn trả mới: {$borrow->ngay_hen_tra->format('d/m/Y')}");
        }
        
        return back()->withErrors(['error' => 'Gia hạn thất bại.']);
    }

    // public function show($id)
    // {
    //     $borrow = Borrow::with(['reader', 'librarian', 'fines'])->findOrFail($id);
    //     return view('admin.borrows.show', compact('borrow'));
    // }
    public function show($id)
{
    $borrow = Borrow::with(['reader', 'librarian', 'items.book'])->findOrFail($id);
$borrowItems = $borrow->items;
    return view('admin.borrows.show', compact('borrow', 'borrowItems'));
}


   public function createItem($borrowId)
{
    $borrow = Borrow::findOrFail($borrowId);
    
    $books = Book::all();
    $librarians = Librarian::all(); // <--- thêm dòng này
    return view('admin.borrows.createitem', compact('borrow', 'books', 'librarians'));
}
public function storeItem(Request $request, $borrowId)
{
    // 1️⃣ Validate
    $request->validate([
        'book_id'        => 'required|exists:books,id',
'inventory_ids'   => 'required|array',
'inventory_ids.*' => 'exists:inventories,id',
        'tien_coc'       => 'nullable|numeric|min:0',
        'tien_thue'      => 'nullable|numeric|min:0',
        'tien_ship'      => 'nullable|numeric|min:0',

        'voucher_id'     => 'nullable|exists:vouchers,id',
        'trang_thai_coc' => 'required|in:cho_xu_ly,da_thu,da_hoan',

        'ngay_muon'      => 'required|date',
        'ngay_hen_tra'   => 'required|date|after_or_equal:ngay_muon',

        'ngay_tra_thuc_te'  => 'nullable|date|after_or_equal:ngay_muon',
        'so_lan_gia_han'    => 'nullable|integer|min:0',
        'ngay_gia_han_cuoi' => 'nullable|date|after_or_equal:ngay_hen_tra',

        'ghi_chu'        => 'nullable|string',
        'librarian_id'   => 'nullable|exists:users,id',
    ]);

    // 2️⃣ Lấy Borrow
    $borrow = Borrow::findOrFail($borrowId);

    // 3️⃣ Chuẩn bị dữ liệu (ép về số nếu null)
    $data = $request->only([
        'book_id', 'inventory_id',
        'tien_coc', 'tien_thue', 'tien_ship',
        'voucher_id', 'trang_thai_coc',
        'trang_thai',
        'ngay_muon', 'ngay_hen_tra',
        'ghi_chu', 'librarian_id'
    ]);

    $data['tien_coc']  = floatval($data['tien_coc'] ?? 0);
    $data['tien_thue'] = floatval($data['tien_thue'] ?? 0);
    $data['tien_ship'] = floatval($data['tien_ship'] ?? 0);

    if (empty($data['librarian_id'])) {
        $data['librarian_id'] = auth()->id();
    }

    // 4️⃣ Tạo BorrowItem
foreach ($request->inventory_ids as $invId) {
    $borrow->items()->create(array_merge($data, [
        'inventorie_id' => $invId // ✅ đổi tên key trùng với cột trong DB
    ]));

}



    // 6️⃣ Tính tổng tiền lại cho Borrow
    $totals = $borrow->items()
        ->selectRaw('
            SUM(COALESCE(tien_coc,0)) as total_coc,
            SUM(COALESCE(tien_thue,0)) as total_thue,
            SUM(COALESCE(tien_ship,0)) as total_ship
        ')
        ->first();

    $borrow->update([
        'tien_coc'  => $totals->total_coc,
        'tien_thue' => $totals->total_thue,
        'tien_ship' => $totals->total_ship,
        'tong_tien' => $totals->total_coc + $totals->total_thue + $totals->total_ship,
    ]);

    return redirect()
        ->route('admin.borrows.edit', $borrow->id)
        ->with('success', 'Đã thêm sách vào phiếu mượn và cập nhật bản thể!');
}

public function updateStatus()
{
    $statuses = $this->borrowItems()->pluck('trang_thai')->toArray();

    if (in_array('Mat sach', $statuses)) {
        $this->trang_thai = 'Mat sach';
    } elseif (in_array('Qua han', $statuses)) {
        $this->trang_thai = 'Qua han';
    } elseif (in_array('Dang muon', $statuses)) {
        $this->trang_thai = 'Dang muon';
    } else {
        $this->trang_thai = 'Da tra';
    }

    $this->save();
}
public function returnItem($id)
{
    $item = \App\Models\BorrowItem::findOrFail($id);

    // Cập nhật trạng thái item trong bảng borrow_items
    $item->update([
        'trang_thai' => 'Da tra',
'ngay_tra_thuc_te' => now()->toDateString(),
    ]);

    return back()->with('success', 'Cập nhật trạng thái sách thành công!');
}


}