<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Reader;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Librarian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy thống kê tổng quan theo đúng như trong ảnh
        $totalBooks = Book::count();
        $totalReaders = Reader::count();
        $totalBorrowingReaders = Borrow::where('trang_thai', 'Dang muon')->count();
        $totalLibrarians = Librarian::count();
        
        // Thống kê bổ sung
        $overdueBooks = Borrow::where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', now()->toDateString())
            ->count();
        
        $totalReservations = \App\Models\Reservation::count();
        $totalReviews = \App\Models\Review::count();
        $totalFines = \App\Models\Fine::where('status', 'pending')->sum('amount');
        
        // Thống kê theo thể loại
        $categoryStats = Category::withCount('books')->get();
        
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
