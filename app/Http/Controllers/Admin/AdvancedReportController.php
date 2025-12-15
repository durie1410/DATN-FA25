<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AdvancedReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display reports dashboard
     */
    public function index()
    {
        $stats = $this->reportService->getDashboardStats();
        $popularBooks = $this->reportService->getPopularBooks(5);
        $activeReaders = $this->reportService->getActiveReaders(5);
        $overdueBooks = $this->reportService->getOverdueBooksReport();

        return view('admin.reports.advanced.index', compact(
            'stats', 
            'popularBooks', 
            'activeReaders', 
            'overdueBooks'
        ));
    }

    /**
     * Get dashboard statistics
     */
    public function dashboardStats(): JsonResponse
    {
        $stats = $this->reportService->getDashboardStats();
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get borrowing trends
     */
    public function borrowingTrends(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $trends = $this->reportService->getBorrowingTrends($days);
        
        return response()->json([
            'success' => true,
            'data' => $trends
        ]);
    }

    /**
     * Get popular books report
     */
    public function popularBooks(Request $request)
    {
        $limit = $request->get('limit', 10);
        $books = $this->reportService->getPopularBooks($limit);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $books
            ]);
        }
        
        return view('admin.reports.advanced.popular-books', compact('books'));
    }

    /**
     * Get active readers report
     */
    public function activeReaders(Request $request)
    {
        $limit = $request->get('limit', 10);
        $readers = $this->reportService->getActiveReaders($limit);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $readers
            ]);
        }
        
        return view('admin.reports.advanced.active-readers', compact('readers'));
    }

    /**
     * Get overdue books report
     */
    public function overdueBooks(Request $request)
    {
        $overdueBooks = $this->reportService->getOverdueBooksReport();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $overdueBooks
            ]);
        }
        
        return view('admin.reports.advanced.overdue-books', compact('overdueBooks'));
    }

    /**
     * Get fine statistics
     */
    public function fineStatistics(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $stats = $this->reportService->getFineStatistics($days);
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get category performance
     */
    public function categoryPerformance(Request $request)
    {
        $performance = $this->reportService->getCategoryPerformance();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
        }
        
        return view('admin.reports.advanced.category-performance', compact('performance'));
    }

    /**
     * Get monthly report
     */
    public function monthlyReport(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        
        $report = $this->reportService->getMonthlyReport($year, $month);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $report
            ]);
        }
        
        return view('admin.reports.advanced.monthly-report', compact('report'));
    }

    /**
     * Get yearly report
     */
    public function yearlyReport(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $report = $this->reportService->getYearlyReport($year);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $report
            ]);
        }
        
        return view('admin.reports.advanced.yearly-report', compact('report'));
    }

    /**
     * Export report
     */
    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:overdue_books,popular_books,active_readers',
            'filename' => 'nullable|string|max:255',
        ]);

        $type = $request->input('type');
        $filename = $request->input('filename');

        // Get data based on type
        switch ($type) {
            case 'overdue_books':
                $data = $this->reportService->getOverdueBooksReport();
                break;
            case 'popular_books':
                $data = $this->reportService->getPopularBooks();
                break;
            case 'active_readers':
                $data = $this->reportService->getActiveReaders();
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid report type'
                ], 400);
        }

        // Log export action
        AuditService::log('report_exported', null, [], [], "Report '{$type}' exported");

        return $this->reportService->exportReport($type, $data, $filename);
    }

    /**
     * Get books by category chart data
     */
    public function booksByCategory(): JsonResponse
    {
        $data = $this->reportService->getBooksByCategory();
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get borrowing trends chart data
     */
    public function borrowingTrendsChart(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $trends = $this->reportService->getBorrowingTrends($days);
        
        // Format data for chart
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Mượn sách',
                    'data' => [],
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                ],
                [
                    'label' => 'Trả sách',
                    'data' => [],
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ]
            ]
        ];

        // Fill chart data
        $borrowsData = [];
        $returnsData = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][] = Carbon::now()->subDays($i)->format('d/m');
            
            $borrowCount = $trends['borrows']->where('date', $date)->first()->count ?? 0;
            $returnCount = $trends['returns']->where('date', $date)->first()->count ?? 0;
            
            $borrowsData[] = $borrowCount;
            $returnsData[] = $returnCount;
        }

        $chartData['datasets'][0]['data'] = $borrowsData;
        $chartData['datasets'][1]['data'] = $returnsData;

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get fine trends chart data
     */
    public function fineTrendsChart(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $stats = $this->reportService->getFineStatistics($days);
        
        // Format data for chart
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Phạt tạo',
                    'data' => [],
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ],
                [
                    'label' => 'Phạt đã trả',
                    'data' => [],
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                ]
            ]
        ];

        // Fill chart data
        $createdData = [];
        $paidData = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][] = Carbon::now()->subDays($i)->format('d/m');
            
            $createdAmount = $stats['fines_created']->where('date', $date)->first()->total_amount ?? 0;
            $paidAmount = $stats['fines_paid']->where('date', $date)->first()->total_amount ?? 0;
            
            $createdData[] = $createdAmount;
            $paidData[] = $paidAmount;
        }

        $chartData['datasets'][0]['data'] = $createdData;
        $chartData['datasets'][1]['data'] = $paidData;

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get real-time statistics
     */
    public function realTimeStats(): JsonResponse
    {
        $today = Carbon::today();
        
        $stats = [
            'today_borrows' => \App\Models\Borrow::whereDate('created_at', $today)->count(),
            'today_returns' => \App\Models\Borrow::whereDate('ngay_tra_thuc_te', $today)->count(),
            'active_borrows' => \App\Models\Borrow::where('trang_thai', 'Dang muon')->count(),
            'overdue_borrows' => \App\Models\Borrow::where('trang_thai', 'Dang muon')
                ->where('ngay_hen_tra', '<', $today)->count(),
            'pending_reservations' => 0, // Reservation model đã bị xóa
            'pending_fines' => \App\Models\Fine::where('status', 'pending')->sum('amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}























