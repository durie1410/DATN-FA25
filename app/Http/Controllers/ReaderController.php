<?php

namespace App\Http\Controllers;

use App\Models\Reader;
use App\Models\Borrow;
use App\Models\Fine;
// use App\Models\Reservation; // Model đã bị xóa
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReadersExport;

class ReaderController extends Controller
{
    public function index(Request $request)
    {
        $query = Reader::query();

        // Tìm kiếm theo tên, email, hoặc mã tác giả
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('so_the_doc_gia', 'like', "%{$keyword}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo giới tính
        if ($request->filled('gioi_tinh')) {
            $query->where('gioi_tinh', $request->gioi_tinh);
        }

        // Lọc theo năm sinh
        if ($request->filled('nam_sinh')) {
            $query->whereYear('ngay_sinh', $request->nam_sinh);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $readers = $query->paginate(15);

        return view('admin.readers.index', compact('readers'));
    }

    public function create()
    {
        return view('admin.readers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required|max:255',
            'email' => 'required|email|unique:readers',
            'so_dien_thoai' => 'required|max:20',
            'so_cccd' => 'required|max:20',
            'ngay_sinh' => 'required|date|before:today',
            'gioi_tinh' => 'required|in:Nam,Nu,Khac',
            'dia_chi' => 'required|max:500',
            'so_the_doc_gia' => 'required|unique:readers|max:20',
            'ngay_cap_the' => 'required|date',
            'ngay_het_han' => 'required|date|after:ngay_cap_the',
            'trang_thai' => 'required|in:Hoat dong,Tam khoa,Het han',
        ]);

        // Tự động tạo mã tác giả nếu không có
        if (empty($request->so_the_doc_gia)) {
            $request->merge(['so_the_doc_gia' => 'DG_' . str_pad(Reader::count() + 1, 6, '0', STR_PAD_LEFT)]);
        }

        Reader::create($request->all());

        return redirect()->route('admin.readers.index')->with('success', 'Thêm tác giả thành công!');
    }

    public function show($id)
    {
        $reader = Reader::with(['borrows.book', 'fines', 'reservations.book'])->findOrFail($id);
        
        // Thống kê của tác giả
        $stats = [
            'total_borrows' => $reader->borrows->count(),
            'active_borrows' => $reader->borrows->where('trang_thai', 'Dang muon')->count(),
            'total_fines' => $reader->fines->sum('amount'),
            'pending_fines' => $reader->fines->where('status', 'pending')->sum('amount'),
            'total_reservations' => $reader->reservations->count(),
            'active_reservations' => $reader->reservations->whereIn('status', ['pending', 'confirmed', 'ready'])->count(),
        ];

        return view('admin.readers.show', compact('reader', 'stats'));
    }

    public function edit($id)
    {
        $reader = Reader::findOrFail($id);
        return view('admin.readers.edit', compact('reader'));
    }

    public function update(Request $request, $id)
    {
        $reader = Reader::findOrFail($id);

        $request->validate([
            'ho_ten' => 'required|max:255',
            'email' => 'required|email|unique:readers,email,' . $id,
            'so_dien_thoai' => 'required|max:20',
            'so_cccd' => 'required|max:20',
            'ngay_sinh' => 'required|date|before:today',
            'gioi_tinh' => 'required|in:Nam,Nu,Khac',
            'dia_chi' => 'required|max:500',
            'so_the_doc_gia' => 'required|unique:readers,so_the_doc_gia,' . $id . '|max:20',
            'ngay_cap_the' => 'required|date',
            'ngay_het_han' => 'required|date|after:ngay_cap_the',
            'trang_thai' => 'required|in:Hoat dong,Tam khoa,Het han',
        ]);

        $reader->update($request->all());

        return redirect()->route('admin.readers.index')->with('success', 'Cập nhật tác giả thành công!');
    }

    public function destroy($id)
    {
        $reader = Reader::findOrFail($id);
        
        // Kiểm tra xem tác giả có đang mượn sách không
        $activeBorrows = $reader->borrows()->where('trang_thai', 'Dang muon')->count();
        if ($activeBorrows > 0) {
            return redirect()->route('admin.readers.index')
                ->with('error', 'Không thể xóa tác giả đang có sách mượn!');
        }

        // Kiểm tra xem tác giả có phạt chưa thanh toán không
        $pendingFines = $reader->fines()->where('status', 'pending')->sum('amount');
        if ($pendingFines > 0) {
            return redirect()->route('admin.readers.index')
                ->with('error', 'Không thể xóa tác giả có phạt chưa thanh toán!');
        }

        $reader->delete();
        return redirect()->route('admin.readers.index')->with('success', 'Xóa tác giả thành công!');
    }

    // Các chức năng bổ sung
    public function renewCard($id)
    {
        $reader = Reader::findOrFail($id);
        
        // Gia hạn thẻ 1 năm
        $newExpiryDate = now()->addYear();
        $reader->update([
            'ngay_het_han' => $newExpiryDate,
            'trang_thai' => 'Hoat dong'
        ]);

        return redirect()->route('admin.readers.show', $id)
            ->with('success', 'Gia hạn thẻ tác giả thành công đến ' . $newExpiryDate->format('d/m/Y'));
    }

    public function suspend($id)
    {
        $reader = Reader::findOrFail($id);
        $reader->update(['trang_thai' => 'Tam khoa']);

        return redirect()->route('admin.readers.show', $id)
            ->with('success', 'Tạm khóa tác giả thành công!');
    }

    public function activate($id)
    {
        $reader = Reader::findOrFail($id);
        $reader->update(['trang_thai' => 'Hoat dong']);

        return redirect()->route('admin.readers.show', $id)
            ->with('success', 'Kích hoạt tác giả thành công!');
    }

    public function export(Request $request)
    {
        return Excel::download(new ReadersExport($request), 'danh_sach_doc_gia_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function print(Request $request)
    {
        $query = Reader::query();

        // Áp dụng các bộ lọc giống như index
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('so_the_doc_gia', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $readers = $query->orderBy('ho_ten')->get();

        return view('admin.readers.print', compact('readers'));
    }

    public function statistics()
    {
        $stats = [
            'total_readers' => Reader::count(),
            'active_readers' => Reader::where('trang_thai', 'Hoat dong')->count(),
            'suspended_readers' => Reader::where('trang_thai', 'Tam khoa')->count(),
            'expired_readers' => Reader::where('trang_thai', 'Het han')->count(),
            'male_readers' => Reader::where('gioi_tinh', 'Nam')->count(),
            'female_readers' => Reader::where('gioi_tinh', 'Nu')->count(),
            'readers_by_year' => Reader::selectRaw('YEAR(ngay_sinh) as year, COUNT(*) as count')
                ->groupBy('year')
                ->orderBy('year')
                ->get(),
            'readers_by_status' => Reader::selectRaw('trang_thai, COUNT(*) as count')
                ->groupBy('trang_thai')
                ->get(),
        ];

        return view('admin.readers.statistics', compact('stats'));
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $readerIds = $request->reader_ids;

        if (empty($readerIds)) {
            return redirect()->route('admin.readers.index')
                ->with('error', 'Vui lòng chọn ít nhất một tác giả!');
        }

        switch ($action) {
            case 'activate':
                Reader::whereIn('id', $readerIds)->update(['trang_thai' => 'Hoat dong']);
                $message = 'Kích hoạt ' . count($readerIds) . ' tác giả thành công!';
                break;
            case 'suspend':
                Reader::whereIn('id', $readerIds)->update(['trang_thai' => 'Tam khoa']);
                $message = 'Tạm khóa ' . count($readerIds) . ' tác giả thành công!';
                break;
            case 'delete':
                // Kiểm tra xem có tác giả nào đang mượn sách không
                $activeBorrows = Borrow::whereIn('reader_id', $readerIds)
                    ->where('trang_thai', 'Dang muon')
                    ->count();
                
                if ($activeBorrows > 0) {
                    return redirect()->route('admin.readers.index')
                        ->with('error', 'Không thể xóa tác giả đang có sách mượn!');
                }

                Reader::whereIn('id', $readerIds)->delete();
                $message = 'Xóa ' . count($readerIds) . ' tác giả thành công!';
                break;
            default:
                return redirect()->route('admin.readers.index')
                    ->with('error', 'Hành động không hợp lệ!');
        }

        return redirect()->route('admin.readers.index')->with('success', $message);
    }
}