<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reader;
use App\Models\Reservation;
use App\Models\Fine;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class StaffDashboardController extends Controller
{
    public function index()
    {
        // Thống kê cơ bản cho staff
        $stats = [
            'total_books' => Book::count(),
            'total_readers' => Reader::count(),
            'active_borrows' => Borrow::where('trang_thai', 'Dang muon')->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'overdue_books' => Borrow::where('trang_thai', 'Dang muon')
                ->where('ngay_hen_tra', '<', now())
                ->count(),
            'total_fines' => Fine::where('status', 'pending')->sum('amount'),
        ];

        // Sách được mượn nhiều nhất
        $popular_books = Book::withCount('borrows')
            ->orderBy('borrows_count', 'asc')
            ->limit(5)
            ->get();

        // Độc giả tích cực
        $active_readers = Reader::withCount('borrows')
            ->orderBy('borrows_count', 'asc')
            ->limit(5)
            ->get();

        // Sách sắp đến hạn trả
        $upcoming_returns = Borrow::with(['book', 'reader'])
            ->where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<=', now()->addDays(3))
            ->orderBy('ngay_hen_tra')
            ->limit(10)
            ->get();

        // Đặt chỗ chờ xử lý
        $pending_reservations = Reservation::with(['book', 'reader'])
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->limit(10)
            ->get();

        // Thống kê theo danh mục
        $category_stats = Category::withCount('books')
            ->orderBy('books_count', 'asc')
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
