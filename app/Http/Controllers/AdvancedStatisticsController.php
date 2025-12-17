<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reader;
use App\Models\Borrow;
use App\Models\Fine;
// use App\Models\Reservation; // Model đã bị xóa
use App\Models\SearchLog;
use App\Models\NotificationLog;
use App\Models\InventoryTransaction;
use App\Models\Category;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvancedStatisticsController extends Controller
{
    /**
     * Dashboard thống kê nâng cao
     */
    public function dashboard()
    {
        try {
            $stats = [
                'overview' => $this->getOverviewStats(),
                'trends' => $this->getTrendStats(),
                'popular_books' => $this->getPopularBooks(),
                'active_readers' => $this->getActiveReaders(),
                'search_analytics' => $this->getSearchAnalytics(),
                'notification_stats' => $this->getNotificationStats(),
                'inventory_stats' => $this->getInventoryStats(),
            ];

            return view('admin.statistics.advanced.dashboard', compact('stats'));
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return view('admin.statistics.advanced.dashboard', [
                'stats' => [
                    'overview' => ['total_books' => 0, 'total_readers' => 0, 'total_borrows' => 0, 'active_borrows' => 0, 'overdue_borrows' => 0, 'total_fines' => 0, 'pending_fines' => 0],
                    'trends' => collect([]),
                    'popular_books' => collect([]),
                    'active_readers' => collect([]),
                    'search_analytics' => ['total_searches' => 0, 'popular_queries' => collect([]), 'searches_by_type' => collect([])],
                    'notification_stats' => ['total_sent' => 0, 'delivery_rate' => 0, 'read_rate' => 0],
                    'inventory_stats' => ['total_transactions' => 0, 'total_value' => 0, 'by_type' => collect([])],
                ]
            ]);
        }
    }

    /**
     * Thống kê tổng quan
     */
    public function overview(Request $request)
    {
        $period = $request->get('period', 'month');
        $fromDate = $this->getDateFromPeriod($period);
        $toDate = Carbon::now();

        $stats = [
            'books' => [
                'total' => Book::count(),
                'active' => Book::where('trang_thai', 'active')->count(),
                'new_this_period' => Book::whereBetween('created_at', [$fromDate, $toDate])->count(),
                'most_borrowed' => $this->getMostBorrowedBooks($fromDate, $toDate),
            ],
            'readers' => [
                'total' => Reader::count(),
                'active' => Reader::where('trang_thai', 'Hoat dong')->count(),
                'new_this_period' => Reader::whereBetween('created_at', [$fromDate, $toDate])->count(),
                'most_active' => $this->getMostActiveReaders($fromDate, $toDate),
            ],
            'borrows' => [
                'total' => Borrow::count(),
                'this_period' => Borrow::whereBetween('ngay_muon', [$fromDate, $toDate])->count(),
                'active' => Borrow::where('trang_thai', 'Dang muon')->count(),
                'overdue' => Borrow::where('trang_thai', 'Dang muon')
                    ->where('ngay_hen_tra', '<', Carbon::today())
                    ->count(),
                'return_rate' => $this->getReturnRate($fromDate, $toDate),
            ],
            'fines' => [
                'total_amount' => Fine::sum('amount'),
                'this_period' => Fine::whereBetween('created_at', [$fromDate, $toDate])->sum('amount'),
                'pending' => Fine::where('status', 'pending')->sum('amount'),
                'paid' => Fine::where('status', 'paid')->sum('amount'),
                'collection_rate' => $this->getFineCollectionRate($fromDate, $toDate),
            ],
        ];

        return response()->json($stats);
    }

    /**
     * Thống kê xu hướng
     */
    public function trends(Request $request)
    {
        $period = $request->get('period', 'month');
        $months = $request->get('months', 12);
        
        $trends = [
            'borrows' => $this->getBorrowTrends($period, $months),
            'readers' => $this->getReaderTrends($period, $months),
            'fines' => $this->getFineTrends($period, $months),
            'search_activity' => $this->getSearchTrends($period, $months),
        ];

        return response()->json($trends);
    }

    /**
     * Thống kê theo thể loại
     */
    public function categoryStats(Request $request)
    {
        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth());
        $toDate = $request->get('to_date', Carbon::now()->endOfMonth());

        $stats = Category::withCount(['books'])
            ->with(['books' => function($query) use ($fromDate, $toDate) {
                $query->withCount(['borrows' => function($q) use ($fromDate, $toDate) {
                    $q->whereBetween('ngay_muon', [$fromDate, $toDate]);
                }]);
            }])
            ->get()
            ->map(function($category) {
                $totalBorrows = $category->books->sum('borrows_count');
                return [
                    'id' => $category->id,
                    'name' => $category->ten_the_loai,
                    'book_count' => $category->books_count,
                    'borrow_count' => $totalBorrows,
                    'average_rating' => $category->books->avg('danh_gia_trung_binh'),
                ];
            })
            ->sortByDesc('borrow_count')
            ->values();

        return response()->json($stats);
    }

    /**
     * Thống kê theo khoa/bộ môn
     */
    public function facultyStats(Request $request)
    {
        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth());
        $toDate = $request->get('to_date', Carbon::now()->endOfMonth());

        $stats = Faculty::withCount(['readers'])
            ->with(['readers' => function($query) use ($fromDate, $toDate) {
                $query->withCount(['borrows' => function($q) use ($fromDate, $toDate) {
                    $q->whereBetween('ngay_muon', [$fromDate, $toDate]);
                }]);
            }])
            ->get()
            ->map(function($faculty) {
                $totalBorrows = $faculty->readers->sum('borrows_count');
                $activeReaders = $faculty->readers->where('trang_thai', 'Hoat dong')->count();
                
                return [
                    'id' => $faculty->id,
                    'name' => $faculty->ten_khoa,
                    'reader_count' => $faculty->readers_count,
                    'active_readers' => $activeReaders,
                    'borrow_count' => $totalBorrows,
                    'average_borrows_per_reader' => $faculty->readers_count > 0 ? 
                        round($totalBorrows / $faculty->readers_count, 2) : 0,
                ];
            })
            ->sortByDesc('borrow_count')
            ->values();

        return response()->json($stats);
    }

    /**
     * Thống kê tìm kiếm
     */
    public function searchStats(Request $request)
    {
        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth());
        $toDate = $request->get('to_date', Carbon::now()->endOfMonth());

        $stats = [
            'total_searches' => SearchLog::whereBetween('created_at', [$fromDate, $toDate])->count(),
            'unique_queries' => SearchLog::whereBetween('created_at', [$fromDate, $toDate])
                ->distinct('query')->count(),
            'searches_by_type' => SearchLog::whereBetween('created_at', [$fromDate, $toDate])
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'popular_queries' => SearchLog::whereBetween('created_at', [$fromDate, $toDate])
                ->selectRaw('query, COUNT(*) as search_count')
                ->groupBy('query')
                ->orderBy('search_count', 'desc')
                ->limit(20)
                ->get(),
            'search_success_rate' => $this->getSearchSuccessRate($fromDate, $toDate),
        ];

        return response()->json($stats);
    }

    /**
     * Thống kê thông báo
     */
    public function notificationStats(Request $request)
    {
        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth());
        $toDate = $request->get('to_date', Carbon::now()->endOfMonth());

        $stats = [
            'total_sent' => NotificationLog::whereBetween('sent_at', [$fromDate, $toDate])->count(),
            'delivery_rate' => $this->getNotificationDeliveryRate($fromDate, $toDate),
            'read_rate' => $this->getNotificationReadRate($fromDate, $toDate),
            'by_type' => NotificationLog::whereBetween('sent_at', [$fromDate, $toDate])
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'by_status' => NotificationLog::whereBetween('sent_at', [$fromDate, $toDate])
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Thống kê kho
     */
    public function inventoryStats(Request $request)
    {
        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth());
        $toDate = $request->get('to_date', Carbon::now()->endOfMonth());

        $stats = [
            'total_transactions' => InventoryTransaction::whereBetween('created_at', [$fromDate, $toDate])->count(),
            'total_value' => 0, // No total_amount column in inventory_transactions table
            'by_type' => InventoryTransaction::whereBetween('created_at', [$fromDate, $toDate])
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'by_status' => InventoryTransaction::whereBetween('created_at', [$fromDate, $toDate])
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'top_books' => $this->getTopInventoryBooks($fromDate, $toDate),
        ];

        return response()->json($stats);
    }

    // Private helper methods

    private function getOverviewStats()
    {
        return [
            'total_books' => Book::count(),
            'total_readers' => Reader::count(),
            'total_borrows' => Borrow::count(),
            'active_borrows' => Borrow::where('trang_thai', 'Dang muon')->count(),
            'overdue_borrows' => Borrow::where('trang_thai', 'Dang muon')
                ->where('ngay_hen_tra', '<', Carbon::today())
                ->count(),
            'total_fines' => Fine::sum('amount'),
            'pending_fines' => Fine::where('status', 'pending')->sum('amount'),
        ];
    }

    private function getTrendStats()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $date->format('Y-m'),
                'borrows' => Borrow::whereYear('ngay_muon', $date->year)
                    ->whereMonth('ngay_muon', $date->month)
                    ->count(),
                'readers' => Reader::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'fines' => Fine::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('amount'),
            ];
        }
        return collect($months);
    }

    private function getPopularBooks($limit = 10)
    {
        return Book::withCount('borrows')
            ->orderBy('borrows_count', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getActiveReaders($limit = 10)
    {
        return Reader::withCount('borrows')
            ->orderBy('borrows_count', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getSearchAnalytics()
    {
        return [
            'total_searches' => SearchLog::count(),
            'popular_queries' => SearchLog::popularQueries(10)->get(),
            'searches_by_type' => SearchLog::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
        ];
    }

    private function getNotificationStats()
    {
        return [
            'total_sent' => NotificationLog::count(),
            'delivery_rate' => $this->getNotificationDeliveryRate(),
            'read_rate' => $this->getNotificationReadRate(),
        ];
    }

    private function getInventoryStats()
    {
        return [
            'total_transactions' => InventoryTransaction::count(),
            'total_value' => 0, // No total_amount column in inventory_transactions table
            'by_type' => InventoryTransaction::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
        ];
    }

    private function getDateFromPeriod($period)
    {
        switch ($period) {
            case 'week':
                return Carbon::now()->subWeek();
            case 'month':
                return Carbon::now()->subMonth();
            case 'quarter':
                return Carbon::now()->subQuarter();
            case 'year':
                return Carbon::now()->subYear();
            default:
                return Carbon::now()->subMonth();
        }
    }

    private function getMostBorrowedBooks($fromDate, $toDate, $limit = 10)
    {
        return Book::withCount(['borrows' => function($query) use ($fromDate, $toDate) {
            $query->whereBetween('ngay_muon', [$fromDate, $toDate]);
        }])
        ->orderBy('borrows_count', 'desc')
        ->limit($limit)
        ->get();
    }

    private function getMostActiveReaders($fromDate, $toDate, $limit = 10)
    {
        return Reader::withCount(['borrows' => function($query) use ($fromDate, $toDate) {
            $query->whereBetween('ngay_muon', [$fromDate, $toDate]);
        }])
        ->orderBy('borrows_count', 'desc')
        ->limit($limit)
        ->get();
    }

    private function getReturnRate($fromDate, $toDate)
    {
        $totalBorrows = Borrow::whereBetween('ngay_muon', [$fromDate, $toDate])->count();
        $returnedBorrows = Borrow::whereBetween('ngay_muon', [$fromDate, $toDate])
            ->where('trang_thai', 'Da tra')
            ->count();
        
        return $totalBorrows > 0 ? round(($returnedBorrows / $totalBorrows) * 100, 2) : 0;
    }

    private function getFineCollectionRate($fromDate, $toDate)
    {
        $totalFines = Fine::whereBetween('created_at', [$fromDate, $toDate])->sum('amount');
        $paidFines = Fine::whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', 'paid')
            ->sum('amount');
        
        return $totalFines > 0 ? round(($paidFines / $totalFines) * 100, 2) : 0;
    }

    private function getBorrowTrends($period, $months)
    {
        $trends = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trends[] = [
                'period' => $date->format('Y-m'),
                'count' => Borrow::whereYear('ngay_muon', $date->year)
                    ->whereMonth('ngay_muon', $date->month)
                    ->count(),
            ];
        }
        return $trends;
    }

    private function getReaderTrends($period, $months)
    {
        $trends = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trends[] = [
                'period' => $date->format('Y-m'),
                'count' => Reader::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        return $trends;
    }

    private function getFineTrends($period, $months)
    {
        $trends = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trends[] = [
                'period' => $date->format('Y-m'),
                'amount' => Fine::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('amount'),
            ];
        }
        return $trends;
    }

    private function getSearchTrends($period, $months)
    {
        $trends = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trends[] = [
                'period' => $date->format('Y-m'),
                'count' => SearchLog::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        return $trends;
    }

    private function getSearchSuccessRate($fromDate, $toDate)
    {
        $totalSearches = SearchLog::whereBetween('created_at', [$fromDate, $toDate])->count();
        $successfulSearches = SearchLog::whereBetween('created_at', [$fromDate, $toDate])
            ->where('results_count', '>', 0)
            ->count();
        
        return $totalSearches > 0 ? round(($successfulSearches / $totalSearches) * 100, 2) : 0;
    }

    private function getNotificationDeliveryRate($fromDate = null, $toDate = null)
    {
        $query = NotificationLog::query();
        if ($fromDate && $toDate) {
            $query->whereBetween('sent_at', [$fromDate, $toDate]);
        }
        
        $totalSent = $query->count();
        $delivered = $query->whereIn('status', ['delivered', 'read'])->count();
        
        return $totalSent > 0 ? round(($delivered / $totalSent) * 100, 2) : 0;
    }

    private function getNotificationReadRate($fromDate = null, $toDate = null)
    {
        $query = NotificationLog::query();
        if ($fromDate && $toDate) {
            $query->whereBetween('sent_at', [$fromDate, $toDate]);
        }
        
        $totalSent = $query->count();
        $read = $query->where('status', 'read')->count();
        
        return $totalSent > 0 ? round(($read / $totalSent) * 100, 2) : 0;
    }

    private function getTopInventoryBooks($fromDate, $toDate, $limit = 10)
    {
        return InventoryTransaction::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('inventory_id, SUM(quantity) as total_quantity')
            ->groupBy('inventory_id')
            ->orderBy('total_quantity', 'desc')
            ->with('inventory')
            ->limit($limit)
            ->get();
    }
}
