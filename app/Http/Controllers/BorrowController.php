<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Reader;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrow::with(['reader', 'book', 'librarian']);

        // Tìm kiếm theo tên tác giả hoặc tên sách
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('reader', function($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%{$keyword}%");
            })->orWhereHas('book', function($q) use ($keyword) {
                $q->where('ten_sach', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $borrows = $query->orderBy('created_at', 'desc')->paginate(10);

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
            'reader_id' => 'required|exists:readers,id',
            'book_id' => 'required|exists:books,id',
            'librarian_id' => 'nullable|exists:users,id',
            'ngay_muon' => 'required|date',
            'ngay_hen_tra' => 'required|date|after:ngay_muon',
            'ghi_chu' => 'nullable|string',
        ]);

        // Kiểm tra sách đã được mượn chưa
        $existingBorrow = Borrow::where('book_id', $request->book_id)
            ->where('trang_thai', 'Dang muon')
            ->first();

        if ($existingBorrow) {
            return back()->withErrors(['book_id' => 'Sách này đang được mượn bởi người khác.']);
        }

        Borrow::create($request->all());

        return redirect()->route('admin.borrows.index')->with('success', 'Cho mượn sách thành công!');
    }

    public function edit($id)
    {
        $borrow = Borrow::findOrFail($id);
        $readers = Reader::where('trang_thai', 'Hoat dong')->get();
        $books = Book::all();
        $librarians = User::where('role', 'admin')->get();
        
        return view('admin.borrows.edit', compact('borrow', 'readers', 'books', 'librarians'));
    }

    public function update(Request $request, $id)
    {
        $borrow = Borrow::findOrFail($id);

        $request->validate([
            'reader_id' => 'required|exists:readers,id',
            'book_id' => 'required|exists:books,id',
            'librarian_id' => 'nullable|exists:users,id',
            'ngay_muon' => 'required|date',
            'ngay_hen_tra' => 'required|date|after:ngay_muon',
            'ngay_tra_thuc_te' => 'nullable|date',
            'trang_thai' => 'required|in:Dang muon,Da tra,Qua han,Mat sach',
            'ghi_chu' => 'nullable|string',
        ]);

        $borrow->update($request->all());

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

    public function show($id)
    {
        $borrow = Borrow::with(['reader', 'book', 'librarian', 'fines'])->findOrFail($id);
        return view('admin.borrows.show', compact('borrow'));
    }
}