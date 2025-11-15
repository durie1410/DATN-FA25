<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Reader;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Librarian;
use App\Services\CacheService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy thống kê từ cache (5 phút)
        $stats = CacheService::getAdminDashboardStats();
        
        // Extract từ array để tương thích với view hiện tại
        $totalBooks = $stats['total_books'];
        $totalReaders = $stats['total_readers'];
        $totalBorrowingReaders = $stats['total_borrowing_readers'];
        $totalLibrarians = $stats['total_librarians'];
        $overdueBooks = $stats['overdue_books'];
        $totalReservations = $stats['total_reservations'];
        $totalReviews = $stats['total_reviews'];
        $totalFines = $stats['total_fines'];
        $categoryStats = $stats['category_stats'];
        
        return view('admin.dashboard', compact(
            'totalBooks',
            'totalReaders',
            'totalBorrowingReaders',
            'totalLibrarians',
            'overdueBooks',
            'totalReservations',
            'totalReviews',
            'totalFines',
            'categoryStats'
        ));
    }
}
