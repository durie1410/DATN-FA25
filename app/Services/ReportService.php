<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Reader;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Fine;
// use App\Models\Reservation; // Model đã bị xóa
use App\Models\Review;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        return [
            'books' => [
                'total' => Book::count(),
                'active' => Book::where('trang_thai', 'active')->count(),
                'this_month' => Book::where('created_at', '>=', $thisMonth)->count(),
                'by_category' => $this->getBooksByCategory(),
            ],
            'readers' => [
                'total' => Reader::count(),
                'active' => Reader::where('trang_thai', 'Hoat dong')->count(),
                'this_month' => Reader::where('created_at', '>=', $thisMonth)->count(),
                'expiring_soon' => Reader::where('ngay_het_han', '<=', $today->addDays(30))->count(),
            ],
            'borrows' => [
                'total' => Borrow::count(),
                'active' => Borrow::where('trang_thai', 'Dang muon')->count(),
                'this_month' => Borrow::where('created_at', '>=', $thisMonth)->count(),
                'overdue' => Borrow::where('trang_thai', 'Dang muon')
                    ->where('ngay_hen_tra', '<', $today)->count(),
                'returned_today' => Borrow::where('ngay_tra_thuc_te', $today)->count(),
            ],
            'fines' => [
                'total_amount' => Fine::where('status', 'pending')->sum('amount'),
                'total_count' => Fine::where('status', 'pending')->count(),
                'this_month' => Fine::where('created_at', '>=', $thisMonth)->sum('amount'),
                'paid_this_month' => Fine::where('status', 'paid')
                    ->where('updated_at', '>=', $thisMonth)->sum('amount'),
            ],
            'reservations' => [
                'total' => 0, // Reservation model đã bị xóa
                'pending' => 0,
                'ready' => 0,
                'this_month' => 0,
            ],
            'reviews' => [
                'total' => Review::count(),
                'verified' => Review::where('is_verified', true)->count(),
                'this_month' => Review::where('created_at', '>=', $thisMonth)->count(),
                'average_rating' => Review::where('is_verified', true)->avg('rating'),
            ],
        ];
    }

    /**
     * Get books by category
     */
    public function getBooksByCategory()
    {
        return Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->ten_the_loai,
                    'count' => $category->books_count,
                    'percentage' => 0 // Will be calculated in frontend
                ];
            });
    }

    /**
     * Get borrowing trends
     */
    public function getBorrowingTrends($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        $borrows = Borrow::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $returns = Borrow::where('ngay_tra_thuc_te', '>=', $startDate)
            ->whereNotNull('ngay_tra_thuc_te')
            ->selectRaw('DATE(ngay_tra_thuc_te) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'borrows' => $borrows,
            'returns' => $returns,
            'period' => $days
        ];
    }

    /**
     * Get popular books
     */
    public function getPopularBooks($limit = 10)
    {
        return Book::with(['category', 'borrows'])
            ->withCount('borrows')
            ->orderBy('borrows_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->ten_sach,
                    'author' => $book->tac_gia,
                    'category' => $book->category->ten_the_loai ?? 'N/A',
                    'borrow_count' => $book->borrows_count,
                    'rating' => $book->danh_gia_trung_binh,
                    'views' => $book->so_luot_xem,
                ];
            });
    }

    /**
     * Get active readers
     */
    public function getActiveReaders($limit = 10)
    {
        return Reader::with(['user', 'borrows'])
            ->withCount(['borrows' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            }])
            ->where('trang_thai', 'Hoat dong')
            ->orderBy('borrows_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($reader) {
                return [
                    'id' => $reader->id,
                    'name' => $reader->ho_ten,
                    'email' => $reader->email,
                    'card_number' => $reader->so_the_doc_gia,
                    'borrows_this_month' => $reader->borrows_count,
                    'expiry_date' => $reader->ngay_het_han,
                ];
            });
    }

    /**
     * Get overdue books report
     */
    public function getOverdueBooksReport()
    {
        $today = Carbon::today();
        
        return Borrow::with(['book', 'reader'])
            ->where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', $today)
            ->orderBy('ngay_hen_tra', 'asc')
            ->get()
            ->map(function($borrow) {
                $daysOverdue = $borrow->ngay_hen_tra->diffInDays($today);
                return [
                    'id' => $borrow->id,
                    'book_title' => $borrow->book->ten_sach,
                    'book_author' => $borrow->book->tac_gia,
                    'reader_name' => $borrow->reader->ho_ten,
                    'reader_email' => $borrow->reader->email,
                    'borrow_date' => $borrow->ngay_muon,
                    'due_date' => $borrow->ngay_hen_tra,
                    'days_overdue' => $daysOverdue,
                    'fine_amount' => $this->calculateFine($daysOverdue),
                ];
            });
    }

    /**
     * Get fine statistics
     */
    public function getFineStatistics($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        $fines = Fine::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $paidFines = Fine::where('status', 'paid')
            ->where('updated_at', '>=', $startDate)
            ->selectRaw('DATE(updated_at) as date, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'fines_created' => $fines,
            'fines_paid' => $paidFines,
            'total_pending' => Fine::where('status', 'pending')->sum('amount'),
            'total_paid' => Fine::where('status', 'paid')->sum('amount'),
            'period' => $days
        ];
    }

    /**
     * Get category performance
     */
    public function getCategoryPerformance()
    {
        return Category::withCount(['books', 'borrows'])
            ->with(['books' => function($query) {
                $query->withCount('borrows');
            }])
            ->get()
            ->map(function($category) {
                $totalBorrows = $category->books->sum('borrows_count');
                return [
                    'id' => $category->id,
                    'name' => $category->ten_the_loai,
                    'book_count' => $category->books_count,
                    'borrow_count' => $totalBorrows,
                    'average_borrows_per_book' => $category->books_count > 0 
                        ? round($totalBorrows / $category->books_count, 2) 
                        : 0,
                ];
            })
            ->sortByDesc('borrow_count')
            ->values();
    }

    /**
     * Get monthly report
     */
    public function getMonthlyReport($year = null, $month = null)
    {
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        return [
            'period' => [
                'year' => $year,
                'month' => $month,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'books' => [
                'added' => Book::whereBetween('created_at', [$startDate, $endDate])->count(),
                'total' => Book::where('created_at', '<=', $endDate)->count(),
            ],
            'readers' => [
                'registered' => Reader::whereBetween('created_at', [$startDate, $endDate])->count(),
                'total' => Reader::where('created_at', '<=', $endDate)->count(),
            ],
            'borrows' => [
                'total' => Borrow::whereBetween('created_at', [$startDate, $endDate])->count(),
                'returned' => Borrow::whereBetween('ngay_tra_thuc_te', [$startDate, $endDate])
                    ->whereNotNull('ngay_tra_thuc_te')->count(),
                'overdue' => Borrow::where('trang_thai', 'Dang muon')
                    ->where('ngay_hen_tra', '<', $endDate)->count(),
            ],
            'fines' => [
                'created' => Fine::whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
                'paid' => Fine::where('status', 'paid')
                    ->whereBetween('updated_at', [$startDate, $endDate])->sum('amount'),
            ],
            'reservations' => [
                'total' => 0, // Reservation model đã bị xóa
                'completed' => 0,
            ],
            'reviews' => [
                'total' => Review::whereBetween('created_at', [$startDate, $endDate])->count(),
                'average_rating' => Review::whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_verified', true)->avg('rating'),
            ],
        ];
    }

    /**
     * Get yearly report
     */
    public function getYearlyReport($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = Carbon::create($year, 12, 31)->endOfYear();

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
            $monthEnd = Carbon::create($year, $month, 1)->endOfMonth();
            
            $monthlyData[] = [
                'month' => $month,
                'month_name' => Carbon::create($year, $month, 1)->format('F'),
                'books_added' => Book::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'readers_registered' => Reader::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'borrows' => Borrow::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'returns' => Borrow::whereBetween('ngay_tra_thuc_te', [$monthStart, $monthEnd])
                    ->whereNotNull('ngay_tra_thuc_te')->count(),
                'fines_created' => Fine::whereBetween('created_at', [$monthStart, $monthEnd])->sum('amount'),
                'fines_paid' => Fine::where('status', 'paid')
                    ->whereBetween('updated_at', [$monthStart, $monthEnd])->sum('amount'),
            ];
        }

        return [
            'year' => $year,
            'monthly_data' => $monthlyData,
            'totals' => [
                'books_added' => array_sum(array_column($monthlyData, 'books_added')),
                'readers_registered' => array_sum(array_column($monthlyData, 'readers_registered')),
                'borrows' => array_sum(array_column($monthlyData, 'borrows')),
                'returns' => array_sum(array_column($monthlyData, 'returns')),
                'fines_created' => array_sum(array_column($monthlyData, 'fines_created')),
                'fines_paid' => array_sum(array_column($monthlyData, 'fines_paid')),
            ]
        ];
    }

    /**
     * Calculate fine amount based on days overdue
     */
    protected function calculateFine($daysOverdue)
    {
        $finePerDay = 5000; // 5,000 VND per day
        return $daysOverdue * $finePerDay;
    }

    /**
     * Export report to CSV
     */
    public function exportReport($type, $data, $filename = null)
    {
        $filename = $filename ?: "report_{$type}_" . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            switch ($type) {
                case 'overdue_books':
                    fputcsv($file, [
                        'ID', 'Tên sách', 'Tác giả', 'Tên độc giả', 'Email', 
                        'Ngày mượn', 'Hạn trả', 'Số ngày quá hạn', 'Tiền phạt'
                    ]);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item['id'],
                            $item['book_title'],
                            $item['book_author'],
                            $item['reader_name'],
                            $item['reader_email'],
                            $item['borrow_date'],
                            $item['due_date'],
                            $item['days_overdue'],
                            number_format($item['fine_amount']) . ' VNĐ'
                        ]);
                    }
                    break;
                    
                case 'popular_books':
                    fputcsv($file, [
                        'ID', 'Tên sách', 'Tác giả', 'Thể loại', 
                        'Số lần mượn', 'Đánh giá', 'Lượt xem'
                    ]);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item['id'],
                            $item['title'],
                            $item['author'],
                            $item['category'],
                            $item['borrow_count'],
                            $item['rating'],
                            $item['views']
                        ]);
                    }
                    break;
                    
                case 'active_readers':
                    fputcsv($file, [
                        'ID', 'Họ tên', 'Email', 'Số thẻ', 
                        'Mượn tháng này', 'Ngày hết hạn'
                    ]);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item['id'],
                            $item['name'],
                            $item['email'],
                            $item['card_number'],
                            $item['borrows_this_month'],
                            $item['expiry_date']
                        ]);
                    }
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}























