<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowItem;
use App\Models\Reader;
use App\Models\Reservation;
use App\Models\Fine;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê cơ bản cho staff - đồng bộ với admin
        $stats = [
            'total_books' => Book::count(),
            'total_readers' => Reader::count(),
            'active_borrows' => Borrow::where('trang_thai', 'Dang muon')->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'overdue_books' => BorrowItem::where('trang_thai', 'Dang muon')
                ->where('ngay_hen_tra', '<', now())
                ->count(),
            'total_fines' => Fine::where('status', 'pending')->sum('amount'),
        ];

        // Sách được mượn nhiều nhất - đồng bộ với admin
        $popular_books = Book::withCount('borrows')
            ->orderBy('borrows_count', 'desc')
            ->limit(5)
            ->get();

        // Độc giả tích cực - đồng bộ với admin
        $active_readers = Reader::withCount('borrows')
            ->orderBy('borrows_count', 'desc')
            ->limit(5)
            ->get();

        // Sách sắp đến hạn trả - sửa lại để hiển thị đúng dữ liệu từ BorrowItem
        $upcoming_returns = BorrowItem::with(['book', 'borrow.reader'])
            ->where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<=', now()->addDays(3))
            ->orderBy('ngay_hen_tra', 'asc')
            ->limit(10)
            ->get();

        // Đặt chỗ chờ xử lý - đồng bộ với admin
        $pending_reservations = Reservation::with(['book', 'reader'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        // Thống kê theo danh mục - đồng bộ với admin
        $category_stats = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->limit(5)
            ->get();

        return view('staff.dashboard', compact(
            'stats',
            'popular_books',
            'active_readers',
            'upcoming_returns',
            'pending_reservations',
            'category_stats'
        ));
    }
}
