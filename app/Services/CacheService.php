<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Publisher;
use App\Models\Book;
use App\Models\Reader;
use App\Models\Borrow;
// use App\Models\Reservation; // Model đã bị xóa
use App\Models\Fine;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache duration in seconds
     */
    const CACHE_DURATION = 3600; // 1 hour
    const SHORT_CACHE_DURATION = 300; // 5 minutes

    /**
     * Get all categories (cached)
     */
    public static function getCategories()
    {
        return Cache::remember('categories_all', self::CACHE_DURATION, function () {
            return Category::orderBy('ten_the_loai')->get();
        });
    }

    /**
     * Get active categories (cached)
     */
    public static function getActiveCategories()
    {
        return Cache::remember('categories_active', self::CACHE_DURATION, function () {
            return Category::where('trang_thai', 'active')
                ->orderBy('ten_the_loai')
                ->get();
        });
    }

    /**
     * Get categories with books count (cached)
     */
    public static function getCategoriesWithCount()
    {
        return Cache::remember('categories_with_count', self::CACHE_DURATION, function () {
            return Category::withCount('books')
                ->orderBy('ten_the_loai')
                ->get();
        });
    }

    /**
     * Get all publishers (cached)
     */
    public static function getPublishers()
    {
        return Cache::remember('publishers_all', self::CACHE_DURATION, function () {
            return Publisher::orderBy('ten_nha_xuat_ban')->get();
        });
    }

    /**
     * Get active publishers (cached)
     */
    public static function getActivePublishers()
    {
        return Cache::remember('publishers_active', self::CACHE_DURATION, function () {
            return Publisher::where('trang_thai', 'active')
                ->orderBy('ten_nha_xuat_ban')
                ->get();
        });
    }

    /**
     * Get dashboard statistics (cached)
     */
    public static function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', self::SHORT_CACHE_DURATION, function () {
            return [
                'total_books' => Book::count(),
                'total_readers' => Reader::count(),
                'active_borrows' => Borrow::where('trang_thai', 'Dang muon')->count(),
                'overdue_books' => Borrow::where('trang_thai', 'Dang muon')
                    ->where('ngay_hen_tra', '<', now()->toDateString())
                    ->count(),
                'total_reservations' => 0, // Reservation model đã bị xóa
                'pending_reservations' => 0,
                'total_reviews' => \App\Models\Review::count(),
                'total_fines' => Fine::where('status', 'pending')->sum('amount'),
            ];
        });
    }

    /**
     * Get admin dashboard statistics (cached)
     */
    public static function getAdminDashboardStats()
    {
        return Cache::remember('admin_dashboard_stats', self::SHORT_CACHE_DURATION, function () {
            return [
                'total_books' => Book::count(),
                'total_readers' => Reader::count(),
                'total_borrowing_readers' => Borrow::where('trang_thai', 'Dang muon')->count(),
                'total_librarians' => \App\Models\Librarian::count(),
                'overdue_books' => Borrow::where('trang_thai', 'Dang muon')
                    ->where('ngay_hen_tra', '<', now()->toDateString())
                    ->count(),
                'total_reservations' => 0, // Reservation model đã bị xóa
                'total_reviews' => \App\Models\Review::count(),
                'total_fines' => Fine::where('status', 'pending')->sum('amount'),
                'category_stats' => Category::withCount('books')->get(),
            ];
        });
    }

    /**
     * Clear all cache
     */
    public static function clearAll()
    {
        Cache::forget('categories_all');
        Cache::forget('categories_active');
        Cache::forget('categories_with_count');
        Cache::forget('publishers_all');
        Cache::forget('publishers_active');
        Cache::forget('dashboard_stats');
        Cache::forget('admin_dashboard_stats');
    }

    /**
     * Clear categories cache
     */
    public static function clearCategories()
    {
        Cache::forget('categories_all');
        Cache::forget('categories_active');
        Cache::forget('categories_with_count');
    }

    /**
     * Clear publishers cache
     */
    public static function clearPublishers()
    {
        Cache::forget('publishers_all');
        Cache::forget('publishers_active');
    }

    /**
     * Clear dashboard cache
     */
    public static function clearDashboard()
    {
        Cache::forget('dashboard_stats');
        Cache::forget('admin_dashboard_stats');
    }
}

