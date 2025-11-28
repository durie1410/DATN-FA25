<?php

namespace App\Http\Controllers;

use App\Models\BorrowItem;
use App\Models\Book;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowItemController extends Controller
{
    /**
     * Hiển thị form chỉnh sửa chi tiết mượn sách
     */
    public function edit($id)
    {
        $item = BorrowItem::with(['book', 'borrow.reader'])->findOrFail($id);
        $books = Book::all();

        return view('admin.borrowsitem.edit', compact('item', 'books'));
    }

    /**
     * Cập nhật chi tiết mượn sách
     */
public function update(Request $request, $id)
{
    $item = BorrowItem::findOrFail($id);

    // Validate dữ liệu, bỏ 'trang_thai' khỏi form để tránh ghi đè
    $validated = $request->except('trang_thai');
    $validated += $request->validate([
        'book_id' => 'required|exists:books,id',
        'ngay_muon' => 'required|date',
        'ngay_hen_tra' => 'required|date|after_or_equal:ngay_muon',
        'tien_coc' => 'required|numeric|min:0',
        'tien_thue' => 'nullable|numeric|min:0',
        'tien_ship' => 'required|numeric|min:0',
        'trang_thai_coc' => 'required|in:cho_xu_ly,da_thu,da_hoan',
        'so_lan_gia_han' => 'nullable|integer|min:0',
        'ghi_chu' => 'nullable|string',
    ]);

    // 1️⃣ Cập nhật BorrowItem
    $item->update($validated);

    // 2️⃣ Tự động đánh dấu quá hạn
    if ($item->ngay_hen_tra && now()->diffInDays($item->ngay_hen_tra, false) < 0) {
        $item->trang_thai = 'Qua han';
        $item->save();
    }

    // 3️⃣ Cập nhật tổng thông tin phiếu mượn
    $borrow = $item->borrow;
    if ($borrow) {
        $borrow->tien_thue = $borrow->borrowItems()->sum('tien_thue');
        $borrow->tien_coc  = $borrow->borrowItems()->sum('tien_coc');
        $borrow->tien_ship = $borrow->borrowItems()->sum('tien_ship');
        $borrow->tong_tien = $borrow->tien_thue + $borrow->tien_coc + $borrow->tien_ship;

        // Cập nhật trạng thái phiếu dựa trên borrow_items
        $statuses = $borrow->borrowItems()->pluck('trang_thai')->toArray();
        if (in_array('Mat sach', $statuses)) {
            $borrow->trang_thai = 'Mat sach';
        } elseif (in_array('Qua han', $statuses)) {
            $borrow->trang_thai = 'Qua han';
        } elseif (in_array('Cho duyet', $statuses) || in_array('Chua nhan', $statuses) || in_array('Dang muon', $statuses)) {
            $borrow->trang_thai = 'chua_hoan_tat';
        } else {
            $borrow->trang_thai = 'Da tra';
        }

        $borrow->save();
    }

    return redirect()
        ->route('admin.borrowitems.show', $item->id)
        ->with('success', 'Cập nhật chi tiết mượn sách thành công.');
}


    /**
     * Hiển thị thông tin chi tiết 1 BorrowItem
     */
   public function show($id)
{
    $borrowItem = BorrowItem::with(['borrow.reader', 'book'])->findOrFail($id);

    $today = Carbon::today(); // giờ = 00:00:00
    $borrowDate = $borrowItem->ngay_muon->copy()->startOfDay();
    $dueDate = $borrowItem->ngay_hen_tra->copy()->startOfDay();

    // Số ngày còn lại (dương = còn hạn, 0 = hết hạn hôm nay, âm = quá hạn)
    $borrowItem->days_remaining = $dueDate->diffInDays($today, false) * -1;

    return view('admin.borrowsitem.show', compact('borrowItem', 'borrowDate', 'dueDate'));
}
public function returnedItems(Request $request)
{
    $query = BorrowItem::where('trang_thai', 'Da tra')
            ->with(['book', 'inventory', 'borrow']);

    // Nếu có tìm kiếm
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;

        $query->whereHas('book', function ($q) use ($keyword) {
            $q->where('ten_sach', 'LIKE', '%' . $keyword . '%');
        });
    }

    $items = $query->orderBy('updated_at', 'desc')->get();

    // Tách hoàn kho và chưa hoàn kho
    $notReturned = $items->filter(fn($i) => $i->inventory && $i->inventory->status != 'Co san');
    $returned = $items->filter(fn($i) => $i->inventory && $i->inventory->status == 'Co san');

    $items = $notReturned->merge($returned);

    return view('admin.borrowsitem.returned', compact('items'));
}



public function approve($id)
{
    $item = \App\Models\BorrowItem::findOrFail($id);

    if ($item->trang_thai !== 'Cho duyet') {
        return back()->with('error', 'Trạng thái không hợp lệ để duyệt.');
    }

    $item->trang_thai = 'Chua nhan';
    $item->save();

    $item->inventory->update([
        'status' => 'Dang muon'
    ]);

    return back()->with('success', 'Đã duyệt và chuyển sách sang trạng thái đang mượn!');
}
public function changeStatus($id)
{
    $item = BorrowItem::findOrFail($id);
    $book = $item->book; // Lấy thông tin sách

    // Chuyển trạng thái sang "Dang muon"
    $item->update([
        'trang_thai' => 'Dang muon',
    ]);

    // Trừ số lượng sách còn lại
    // if ($book->so_luong > 0) {
    //     $book->decrement('so_luong'); // giảm 1
    // } else {
    //     return back()->with('error', 'Số lượng sách không đủ để mượn!');
    // }

    return back()->with('success', 'Cập nhật trạng thái thành công và trừ số lượng sách!');
}











public function markLost($id)
{
    $item = BorrowItem::findOrFail($id);
    $book = $item->book;
    $inventory = $item->inventory;

    $gia = $book->gia;
    $loai = $book->loai_sach;              // binh_thuong | quy | tham_khao
    $tinhTrang = $inventory->condition;    // Moi, Tot, Trung binh, Cu, Hong
    $tienCoc = $item->tien_coc;            // cọc đã thu lúc mượn

    /** ---- 1. TÍNH TIỀN PHẠT BAN ĐẦU ---- **/
    if ($loai === 'quy') {
        // Sách quý phạt 100%
        $phatGoc = $gia;
    } else {
        switch ($tinhTrang) {
            case 'Moi':
            case 'Tot':
                $phatGoc = round($gia * 0.8);
                break;

            case 'Trung binh':
            case 'Cu':
            case 'Hong':
                $phatGoc = round($gia * 0.7);
                break;

            default:
                $phatGoc = round($gia * 0.7);
        }
    }

    /** ---- 2. TRỪ TIỀN CỌC ---- **/
    $phatSauCoc = $phatGoc - $tienCoc;
    if ($phatSauCoc < 0) {
        $phatSauCoc = 0;
    }

    /** ---- 3. Cập nhật BorrowItem ---- **/
    $item->update([
        'trang_thai' => 'Mat sach',
        'tien_phat'  => $phatSauCoc
    ]);

    /** ---- 4. Cập nhật inventory ---- **/
    if ($inventory) {
        $inventory->update([
            'status' => 'Mat'
        ]);
    }

    /** ---- 5. Trừ số lượng sách ---- **/
    if ($book->so_luong > 0) {
        $book->decrement('so_luong');
    }

    /** ---- 6. Ghi vào bảng fines ---- **/
    Fine::create([
        'borrow_id'      => $item->borrow_id,
        'borrow_item_id' => $item->id,          // <-- thêm dòng này
        'reader_id'      => $item->borrow->reader_id,
        'amount'         => $phatSauCoc,
        'type'           => 'lost_book',
        'description'    => 'Mất sách: '.$book->ten_sach.', tình trạng: '.$tinhTrang,
        'status'         => 'pending',
        'due_date'       => now()->addDays(7),
        'created_by'     => auth()->id()
    ]);

    return back()->with(
        'success',
        "Báo mất sách thành công! Phạt: " . number_format($phatSauCoc) . "đ (đã trừ cọc)"
    );
}









public function reportDamage($id)
{
    $item = BorrowItem::with(['book', 'inventory', 'borrow'])->findOrFail($id);

    $book = $item->book;
    $inventory = $item->inventory;

    $gia = $book->gia;
    $loai = $book->loai_sach;           // bình_thuong | quy | tham_khao
    $tinhTrangKhiMuon = $inventory->condition;   // Moi | Tot | Trung binh | Cu | Hong
    $tienCoc = $item->tien_coc;

    /**
     * ----------------------------
     * 1. TÍNH TIỀN PHẠT
     * ----------------------------
     * Bạn yêu cầu:
     *  - Sách bình thường: 80% nếu mới/tốt, 70% nếu cũ
     *  - Sách quý: 100%
     *  - Sách tham khảo: dùng 80%
     */

    if ($loai === 'quy') {
        $phatGoc = $gia; // 100%
    } else {
        switch ($tinhTrangKhiMuon) {
            case 'Moi':
            case 'Tot':
                $phatGoc = round($gia * 0.8);
                break;

            case 'Trung binh':
            case 'Cu':
            case 'Hong':
                $phatGoc = round($gia * 0.7);
                break;

            default:
                $phatGoc = round($gia * 0.7);
        }
    }

    /**
     * ----------------------------
     * 2. TRỪ TIỀN CỌC
     * ----------------------------
     */
    $phatSauCoc = max($phatGoc - $tienCoc, 0);

    /**
     * ----------------------------
     * 3. CẬP NHẬT borrow_items
     * ----------------------------
     */
    $item->update([
        'trang_thai' => 'Hong',
        'tien_phat' => $phatSauCoc,
    ]);

    /**
     * ----------------------------
     * 4. CẬP NHẬT inventory
     * ----------------------------
     */
    if ($inventory) {
        $inventory->update([
            'status' => 'Hong',
        ]);
    }

    /**
     * ----------------------------
     * 5. LƯU VÀO fines
     * ----------------------------
     */
    Fine::create([
        'borrow_id'      => $item->borrow_id,
        'borrow_item_id' => $item->id,
        'reader_id'      => $item->borrow->reader_id,
        'amount'         => $phatSauCoc,
        'type'           => 'damaged_book',
        'description'    => 'Sách hỏng: '.$book->ten_sach.', tình trạng khi mượn: '.$tinhTrangKhiMuon,
        'status'         => 'pending',
        'due_date'       => now()->addDays(7),
        'created_by'     => auth()->id(),
    ]);

    return back()->with(
        'success',
        "Đã báo hỏng sách! Tiền phạt: " . number_format($phatSauCoc) . "đ (đã trừ cọc)"
    );
}


}
