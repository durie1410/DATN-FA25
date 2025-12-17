<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\InventoryReceipt;
use App\Models\Borrow;
use App\Models\BorrowItem;
use App\Models\Fine;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Reader;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryReportController extends Controller
{
    /**
     * Trang chủ báo cáo kho sách
     */
    public function index()
    {
        return view('admin.reports.inventory.index');
    }

    /**
     * BC01 - Thống kê số lượng sách
     * Theo dõi tình trạng kho sách tổng thể và chi tiết theo nhiều tiêu chí
     */
    public function bookStatistics(Request $request)
    {
        $query = Inventory::with(['book.category', 'book.publisher']);

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->whereHas('book', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Lọc theo tác giả
        if ($request->filled('tac_gia')) {
            $query->whereHas('book', function($q) use ($request) {
                $q->where('tac_gia', 'like', '%' . $request->tac_gia . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo tình trạng
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Thống kê tổng quan
        $totalBooks = Inventory::count();
        $availableBooks = Inventory::where('status', 'Co san')->count();
        $borrowedBooks = Inventory::where('status', 'Dang muon')->count();
        $damagedBooks = Inventory::where('status', 'Hong')->count();
        $lostBooks = Inventory::where('status', 'Mat')->count();
        $disposedBooks = Inventory::where('status', 'Thanh ly')->count();

        // Thống kê theo danh mục
        $categoryStats = Category::withCount([
            'books' => function($query) {
                $query->whereHas('inventories');
            }
        ])->get()->map(function($category) {
            $category->available_count = Inventory::whereHas('book', function($q) use ($category) {
                $q->where('category_id', $category->id);
            })->where('status', 'Co san')->count();
            
            $category->borrowed_count = Inventory::whereHas('book', function($q) use ($category) {
                $q->where('category_id', $category->id);
            })->where('status', 'Dang muon')->count();
            
            return $category;
        });

        // Thống kê theo tác giả
        $authorStats = Book::select('tac_gia', DB::raw('count(*) as total'))
            ->whereHas('inventories')
            ->groupBy('tac_gia')
            ->orderBy('total', 'desc')
            ->limit(20)
            ->get()
            ->map(function($book) {
                $book->available_count = Inventory::whereHas('book', function($q) use ($book) {
                    $q->where('tac_gia', $book->tac_gia);
                })->where('status', 'Co san')->count();
                
                $book->borrowed_count = Inventory::whereHas('book', function($q) use ($book) {
                    $q->where('tac_gia', $book->tac_gia);
                })->where('status', 'Dang muon')->count();
                
                return $book;
            });

        // Thống kê theo trạng thái
        $statusStats = Inventory::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Thống kê theo tình trạng
        $conditionStats = Inventory::select('condition', DB::raw('count(*) as count'))
            ->groupBy('condition')
            ->get();

        $inventories = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::all();

        return view('admin.reports.inventory.book-statistics', compact(
            'totalBooks',
            'availableBooks',
            'borrowedBooks',
            'damagedBooks',
            'lostBooks',
            'disposedBooks',
            'categoryStats',
            'authorStats',
            'statusStats',
            'conditionStats',
            'inventories',
            'categories'
        ));
    }

    /**
     * BC02 - Báo cáo số lượng sách trả và mượn
     * Phân tích hoạt động mượn và trả sách theo thời gian
     */
    public function borrowReturnReport(Request $request)
    {
        $fromDate = $request->filled('from_date') ? $request->from_date : Carbon::now()->startOfMonth();
        $toDate = $request->filled('to_date') ? $request->to_date : Carbon::now()->endOfMonth();

        // Tổng số sách đã mượn trong kỳ
        $totalBorrowed = Borrow::whereBetween('ngay_muon', [$fromDate, $toDate])->count();

        // Tổng số sách đã trả trong kỳ
        $totalReturned = Borrow::whereHas('items', function($query) use ($fromDate, $toDate) {
                $query->whereBetween('ngay_tra_thuc_te', [$fromDate, $toDate])
                      ->whereNotNull('ngay_tra_thuc_te');
            })
            ->count();

        // Tỷ lệ mượn/trả
        $borrowReturnRatio = $totalBorrowed > 0 ? round(($totalReturned / $totalBorrowed) * 100, 2) : 0;

        // Thống kê theo ngày
        $dailyStats = [];
        $currentDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        while ($currentDate <= $endDate) {
            $dailyStats[] = [
                'date' => $currentDate->format('Y-m-d'),
                'date_label' => $currentDate->format('d/m/Y'),
                'borrowed' => Borrow::whereDate('ngay_muon', $currentDate->toDateString())->count(),
                'returned' => Borrow::whereHas('items', function($query) use ($currentDate) {
                        $query->whereDate('ngay_tra_thuc_te', $currentDate->toDateString())
                              ->whereNotNull('ngay_tra_thuc_te');
                    })
                    ->count(),
            ];
            $currentDate->addDay();
        }

        // Thống kê theo tuần
        $weeklyStats = [];
        $startWeek = Carbon::parse($fromDate)->startOfWeek();
        $endWeek = Carbon::parse($toDate)->endOfWeek();

        while ($startWeek <= $endWeek) {
            $weekEnd = $startWeek->copy()->endOfWeek();
            if ($weekEnd > $endDate) {
                $weekEnd = Carbon::parse($toDate);
            }

            $weeklyStats[] = [
                'week' => $startWeek->format('Y-W'),
                'week_label' => $startWeek->format('d/m/Y') . ' - ' . $weekEnd->format('d/m/Y'),
                'borrowed' => Borrow::whereBetween('ngay_muon', [$startWeek->toDateString(), $weekEnd->toDateString()])->count(),
                'returned' => Borrow::whereHas('items', function($query) use ($startWeek, $weekEnd) {
                        $query->whereBetween('ngay_tra_thuc_te', [$startWeek->toDateString(), $weekEnd->toDateString()])
                              ->whereNotNull('ngay_tra_thuc_te');
                    })
                    ->count(),
            ];
            $startWeek->addWeek();
        }

        // Thống kê theo tháng
        $monthlyStats = [];
        $startMonth = Carbon::parse($fromDate)->startOfMonth();
        $endMonth = Carbon::parse($toDate)->endOfMonth();

        while ($startMonth <= $endMonth) {
            $monthEnd = $startMonth->copy()->endOfMonth();
            if ($monthEnd > $endDate) {
                $monthEnd = Carbon::parse($toDate);
            }

            $monthlyStats[] = [
                'month' => $startMonth->format('Y-m'),
                'month_label' => $startMonth->format('m/Y'),
                'borrowed' => Borrow::whereBetween('ngay_muon', [$startMonth->toDateString(), $monthEnd->toDateString()])->count(),
                'returned' => Borrow::whereHas('items', function($query) use ($startMonth, $monthEnd) {
                        $query->whereBetween('ngay_tra_thuc_te', [$startMonth->toDateString(), $monthEnd->toDateString()])
                              ->whereNotNull('ngay_tra_thuc_te');
                    })
                    ->count(),
            ];
            $startMonth->addMonth();
        }

        // Top sách được mượn nhiều nhất
        $topBorrowedBooks = BorrowItem::with('book')
            ->whereHas('borrow', function($q) use ($fromDate, $toDate) {
                $q->whereBetween('ngay_muon', [$fromDate, $toDate]);
            })
            ->select('book_id', DB::raw('count(*) as borrow_count'))
            ->groupBy('book_id')
            ->orderBy('borrow_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.inventory.borrow-return', compact(
            'totalBorrowed',
            'totalReturned',
            'borrowReturnRatio',
            'dailyStats',
            'weeklyStats',
            'monthlyStats',
            'topBorrowedBooks',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * BC03 - Báo cáo số lượng sách nhập
     * Theo dõi hoạt động bổ sung tài liệu
     */
    public function importReport(Request $request)
    {
        $fromDate = $request->filled('from_date') ? $request->from_date : Carbon::now()->startOfMonth();
        $toDate = $request->filled('to_date') ? $request->to_date : Carbon::now()->endOfMonth();

        // Tổng số lượng sách nhập
        $totalImported = InventoryTransaction::where('type', 'Nhap kho')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->sum('quantity');

        // Tổng số phiếu nhập
        $totalReceipts = InventoryReceipt::whereBetween('receipt_date', [$fromDate, $toDate])
            ->where('status', 'approved')
            ->count();

        // Tổng giá trị nhập
        $totalValue = InventoryReceipt::whereBetween('receipt_date', [$fromDate, $toDate])
            ->where('status', 'approved')
            ->sum('total_price');

        // Phân tích theo nhà cung cấp
        $supplierStats = InventoryReceipt::whereBetween('receipt_date', [$fromDate, $toDate])
            ->where('status', 'approved')
            ->select('supplier', DB::raw('sum(quantity) as total_quantity'), DB::raw('sum(total_price) as total_value'))
            ->groupBy('supplier')
            ->orderBy('total_quantity', 'desc')
            ->get();

        // Phân tích theo danh mục
        $categoryStats = InventoryReceipt::with('book.category')
            ->whereBetween('receipt_date', [$fromDate, $toDate])
            ->where('status', 'approved')
            ->get()
            ->groupBy(function($receipt) {
                return $receipt->book->category_id ?? 'unknown';
            })
            ->map(function($receipts, $categoryId) {
                $firstReceipt = $receipts->first();
                $category = $firstReceipt && $firstReceipt->book ? $firstReceipt->book->category : null;
                return [
                    'category' => $category,
                    'quantity' => $receipts->sum('quantity'),
                    'value' => $receipts->sum('total_price'),
                ];
            })
            ->sortByDesc('quantity')
            ->values();

        // Phân tích theo tác giả
        $authorStats = InventoryReceipt::with('book')
            ->whereBetween('receipt_date', [$fromDate, $toDate])
            ->where('status', 'approved')
            ->get()
            ->groupBy(function($receipt) {
                return $receipt->book->tac_gia ?? 'Không xác định';
            })
            ->map(function($receipts, $author) {
                return [
                    'author' => $author,
                    'quantity' => $receipts->sum('quantity'),
                    'value' => $receipts->sum('total_price'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(20)
            ->values();

        // Thống kê theo tháng
        $monthlyStats = [];
        $startMonth = Carbon::parse($fromDate)->startOfMonth();
        $endMonth = Carbon::parse($toDate)->endOfMonth();

        while ($startMonth <= $endMonth) {
            $monthEnd = $startMonth->copy()->endOfMonth();
            if ($monthEnd > $toDate) {
                $monthEnd = Carbon::parse($toDate);
            }

            $monthlyStats[] = [
                'month' => $startMonth->format('Y-m'),
                'month_label' => $startMonth->format('m/Y'),
                'quantity' => InventoryReceipt::whereBetween('receipt_date', [$startMonth->toDateString(), $monthEnd->toDateString()])
                    ->where('status', 'approved')
                    ->sum('quantity'),
                'value' => InventoryReceipt::whereBetween('receipt_date', [$startMonth->toDateString(), $monthEnd->toDateString()])
                    ->where('status', 'approved')
                    ->sum('total_price'),
                'receipts' => InventoryReceipt::whereBetween('receipt_date', [$startMonth->toDateString(), $monthEnd->toDateString()])
                    ->where('status', 'approved')
                    ->count(),
            ];
            $startMonth->addMonth();
        }

        // Chi tiết phiếu nhập
        $receipts = InventoryReceipt::with(['book', 'receiver', 'approver'])
            ->whereBetween('receipt_date', [$fromDate, $toDate])
            ->where('status', 'approved')
            ->orderBy('receipt_date', 'desc')
            ->paginate(20);

        return view('admin.reports.inventory.import', compact(
            'totalImported',
            'totalReceipts',
            'totalValue',
            'supplierStats',
            'categoryStats',
            'authorStats',
            'monthlyStats',
            'receipts',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * BC04 - Báo cáo số lượng sách hủy
     * Theo dõi việc loại bỏ sách, phân tích lý do hủy
     */
    public function disposalReport(Request $request)
    {
        $fromDate = $request->filled('from_date') ? $request->from_date : Carbon::now()->startOfMonth();
        $toDate = $request->filled('to_date') ? $request->to_date : Carbon::now()->endOfMonth();

        // Tổng số lượng sách hủy
        $totalDisposed = InventoryTransaction::where('type', 'Thanh ly')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->sum('quantity');

        // Phân tích theo lý do hủy (từ reason trong transaction)
        $reasonStats = InventoryTransaction::where('type', 'Thanh ly')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->select('reason', DB::raw('count(*) as count'), DB::raw('sum(quantity) as total_quantity'))
            ->groupBy('reason')
            ->get();

        // Phân tích theo trạng thái trước khi hủy
        $statusBeforeStats = InventoryTransaction::where('type', 'Thanh ly')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->select('status_before', DB::raw('count(*) as count'))
            ->groupBy('status_before')
            ->get();

        // Phân tích theo danh mục
        $categoryStats = InventoryTransaction::with('inventory.book.category')
            ->where('type', 'Thanh ly')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get()
            ->groupBy(function($transaction) {
                return $transaction->inventory->book->category->ten_the_loai ?? 'Không xác định';
            })
            ->map(function($transactions, $category) {
                return [
                    'category' => $category,
                    'count' => $transactions->count(),
                    'quantity' => $transactions->sum('quantity'),
                ];
            })
            ->sortByDesc('quantity')
            ->values();

        // Thống kê theo tháng
        $monthlyStats = [];
        $startMonth = Carbon::parse($fromDate)->startOfMonth();
        $endMonth = Carbon::parse($toDate)->endOfMonth();

        while ($startMonth <= $endMonth) {
            $monthEnd = $startMonth->copy()->endOfMonth();
            if ($monthEnd > $toDate) {
                $monthEnd = Carbon::parse($toDate);
            }

            $monthlyStats[] = [
                'month' => $startMonth->format('Y-m'),
                'month_label' => $startMonth->format('m/Y'),
                'quantity' => InventoryTransaction::where('type', 'Thanh ly')
                    ->whereBetween('created_at', [$startMonth->toDateString(), $monthEnd->toDateString()])
                    ->sum('quantity'),
                'count' => InventoryTransaction::where('type', 'Thanh ly')
                    ->whereBetween('created_at', [$startMonth->toDateString(), $monthEnd->toDateString()])
                    ->count(),
            ];
            $startMonth->addMonth();
        }

        // Chi tiết giao dịch hủy
        $transactions = InventoryTransaction::with(['inventory.book', 'performer'])
            ->where('type', 'Thanh ly')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.inventory.disposal', compact(
            'totalDisposed',
            'reasonStats',
            'statusBeforeStats',
            'categoryStats',
            'monthlyStats',
            'transactions',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * BC05 - Báo cáo tiền phạt
     * Theo dõi hiệu quả thu tiền phạt và phân tích các loại vi phạm
     */
    public function fineReport(Request $request)
    {
        $fromDate = $request->filled('from_date') ? $request->from_date : Carbon::now()->startOfMonth();
        $toDate = $request->filled('to_date') ? $request->to_date : Carbon::now()->endOfMonth();

        // Tổng số tiền phạt
        $totalFines = Fine::whereBetween('created_at', [$fromDate, $toDate])->sum('amount');

        // Tổng số tiền đã thanh toán
        $totalPaid = Fine::whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', 'paid')
            ->sum('amount');

        // Tổng số tiền chưa thanh toán
        $totalPending = Fine::whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', 'pending')
            ->sum('amount');

        // Tổng số tiền đã miễn giảm
        $totalWaived = Fine::whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', 'waived')
            ->sum('amount');

        // Tỷ lệ thanh toán
        $paymentRate = $totalFines > 0 ? round(($totalPaid / $totalFines) * 100, 2) : 0;

        // Phân tích theo loại phạt
        $typeStats = Fine::whereBetween('created_at', [$fromDate, $toDate])
            ->select('type', DB::raw('count(*) as count'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('type')
            ->get()
            ->map(function($stat) {
                $typeLabels = [
                    'late_return' => 'Trả muộn',
                    'damaged_book' => 'Làm hỏng sách',
                    'lost_book' => 'Mất sách',
                    'other' => 'Khác',
                ];
                $stat->type_label = $typeLabels[$stat->type] ?? $stat->type;
                return $stat;
            });

        // Phân tích theo trạng thái thanh toán
        $statusStats = Fine::whereBetween('created_at', [$fromDate, $toDate])
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('status')
            ->get()
            ->map(function($stat) {
                $statusLabels = [
                    'pending' => 'Chưa thanh toán',
                    'paid' => 'Đã thanh toán',
                    'waived' => 'Đã miễn giảm',
                    'cancelled' => 'Đã hủy',
                ];
                $stat->status_label = $statusLabels[$stat->status] ?? $stat->status;
                return $stat;
            });

        // Thống kê theo tháng
        $monthlyStats = [];
        $startMonth = Carbon::parse($fromDate)->startOfMonth();
        $endMonth = Carbon::parse($toDate)->endOfMonth();

        while ($startMonth <= $endMonth) {
            $monthEnd = $startMonth->copy()->endOfMonth();
            if ($monthEnd > $toDate) {
                $monthEnd = Carbon::parse($toDate);
            }

            $monthlyStats[] = [
                'month' => $startMonth->format('Y-m'),
                'month_label' => $startMonth->format('m/Y'),
                'total' => Fine::whereBetween('created_at', [$startMonth->toDateString(), $monthEnd->toDateString()])->sum('amount'),
                'paid' => Fine::whereBetween('created_at', [$startMonth->toDateString(), $monthEnd->toDateString()])
                    ->where('status', 'paid')
                    ->sum('amount'),
                'pending' => Fine::whereBetween('created_at', [$startMonth->toDateString(), $monthEnd->toDateString()])
                    ->where('status', 'pending')
                    ->sum('amount'),
                'count' => Fine::whereBetween('created_at', [$startMonth->toDateString(), $monthEnd->toDateString()])->count(),
            ];
            $startMonth->addMonth();
        }

        // Top độc giả bị phạt nhiều nhất
        $topReaders = Fine::with('reader')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->select('reader_id', DB::raw('count(*) as fine_count'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('reader_id')
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get();

        // Chi tiết khoản phạt
        $fines = Fine::with(['reader', 'borrow.items.book', 'creator'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.inventory.fine', compact(
            'totalFines',
            'totalPaid',
            'totalPending',
            'totalWaived',
            'paymentRate',
            'typeStats',
            'statusStats',
            'monthlyStats',
            'topReaders',
            'fines',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * BC06 - Báo cáo sách trả muộn
     * Theo dõi các trường hợp trả sách quá hạn
     */
    public function lateReturnReport(Request $request)
    {
        $fromDate = $request->filled('from_date') ? $request->from_date : Carbon::now()->startOfMonth();
        $toDate = $request->filled('to_date') ? $request->to_date : Carbon::now()->endOfMonth();

        // Tổng số sách trả muộn
        $totalLateReturns = Borrow::where(function($query) use ($fromDate, $toDate) {
                $query->where('trang_thai', 'Qua han')
                    ->orWhere(function($q) use ($fromDate, $toDate) {
                        $q->where('trang_thai', 'Da tra')
                            ->whereHas('items', function($itemQuery) use ($fromDate, $toDate) {
                                $itemQuery->whereNotNull('ngay_tra_thuc_te')
                                          ->whereRaw('ngay_tra_thuc_te > ngay_hen_tra')
                                          ->whereBetween('ngay_tra_thuc_te', [$fromDate, $toDate]);
                            });
                    });
            })
            ->count();

        // Số sách đang quá hạn chưa trả
        $currentOverdue = Borrow::where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', now()->toDateString())
            ->count();

        // Phân tích theo độc giả
        $readerStats = Borrow::with('reader')
            ->where(function($query) use ($fromDate, $toDate) {
                $query->where('trang_thai', 'Qua han')
                    ->orWhere(function($q) use ($fromDate, $toDate) {
                        $q->where('trang_thai', 'Da tra')
                            ->whereHas('items', function($itemQuery) use ($fromDate, $toDate) {
                                $itemQuery->whereNotNull('ngay_tra_thuc_te')
                                          ->whereRaw('ngay_tra_thuc_te > ngay_hen_tra')
                                          ->whereBetween('ngay_tra_thuc_te', [$fromDate, $toDate]);
                            });
                    });
            })
            ->select('reader_id', DB::raw('count(*) as late_count'))
            ->groupBy('reader_id')
            ->orderBy('late_count', 'desc')
            ->limit(20)
            ->get();

        // Phân tích theo phòng mượn (lấy từ reader department)
        $departmentStats = Borrow::with('reader.department')
            ->where(function($query) use ($fromDate, $toDate) {
                $query->where('trang_thai', 'Qua han')
                    ->orWhere(function($q) use ($fromDate, $toDate) {
                        $q->where('trang_thai', 'Da tra')
                            ->whereHas('items', function($itemQuery) use ($fromDate, $toDate) {
                                $itemQuery->whereNotNull('ngay_tra_thuc_te')
                                          ->whereRaw('ngay_tra_thuc_te > ngay_hen_tra')
                                          ->whereBetween('ngay_tra_thuc_te', [$fromDate, $toDate]);
                            });
                    });
            })
            ->get()
            ->groupBy(function($borrow) {
                return $borrow->reader && $borrow->reader->department ? $borrow->reader->department->ten_phong_ban : 'Không xác định';
            })
            ->map(function($borrows, $department) {
                return [
                    'department' => $department,
                    'count' => $borrows->count(),
                ];
            })
            ->sortByDesc('count')
            ->values();

        // Thống kê số ngày quá hạn
        $overdueDaysStats = Borrow::where(function($query) use ($fromDate, $toDate) {
                $query->where('trang_thai', 'Qua han')
                    ->orWhere(function($q) use ($fromDate, $toDate) {
                        $q->where('trang_thai', 'Da tra')
                            ->whereHas('items', function($itemQuery) use ($fromDate, $toDate) {
                                $itemQuery->whereNotNull('ngay_tra_thuc_te')
                                          ->whereRaw('ngay_tra_thuc_te > ngay_hen_tra')
                                          ->whereBetween('ngay_tra_thuc_te', [$fromDate, $toDate]);
                            });
                    });
            })
            ->get()
            ->map(function($borrow) {
                $dueDate = Carbon::parse($borrow->ngay_hen_tra);
                $returnDate = $borrow->ngay_tra_thuc_te ? Carbon::parse($borrow->ngay_tra_thuc_te) : now();
                $borrow->overdue_days = max(0, $dueDate->diffInDays($returnDate, false));
                return $borrow;
            })
            ->groupBy(function($borrow) {
                $days = $borrow->overdue_days;
                if ($days <= 7) return '1-7 ngày';
                if ($days <= 14) return '8-14 ngày';
                if ($days <= 30) return '15-30 ngày';
                return 'Trên 30 ngày';
            })
            ->map(function($borrows, $range) {
                return [
                    'range' => $range,
                    'count' => $borrows->count(),
                ];
            });

        // Thống kê theo tháng
        $monthlyStats = [];
        $startMonth = Carbon::parse($fromDate)->startOfMonth();
        $endMonth = Carbon::parse($toDate)->endOfMonth();

        while ($startMonth <= $endMonth) {
            $monthEnd = $startMonth->copy()->endOfMonth();
            if ($monthEnd > $toDate) {
                $monthEnd = Carbon::parse($toDate);
            }

            $monthlyStats[] = [
                'month' => $startMonth->format('Y-m'),
                'month_label' => $startMonth->format('m/Y'),
                'count' => Borrow::where(function($query) use ($startMonth, $monthEnd) {
                    $query->where('trang_thai', 'Qua han')
                        ->whereBetween('ngay_hen_tra', [$startMonth->toDateString(), $monthEnd->toDateString()])
                        ->orWhere(function($q) use ($startMonth, $monthEnd) {
                            $q->where('trang_thai', 'Da tra')
                                ->whereNotNull('ngay_tra_thuc_te')
                                ->whereRaw('ngay_tra_thuc_te > ngay_hen_tra')
                                ->whereBetween('ngay_tra_thuc_te', [$startMonth->toDateString(), $monthEnd->toDateString()]);
                        });
                })->count(),
            ];
            $startMonth->addMonth();
        }

        // Chi tiết sách trả muộn
        $lateReturns = Borrow::with(['reader', 'items.book', 'reader.department'])
            ->where(function($query) use ($fromDate, $toDate) {
                $query->where('trang_thai', 'Qua han')
                    ->orWhere(function($q) use ($fromDate, $toDate) {
                        $q->where('trang_thai', 'Da tra')
                            ->whereHas('items', function($itemQuery) use ($fromDate, $toDate) {
                                $itemQuery->whereNotNull('ngay_tra_thuc_te')
                                          ->whereRaw('ngay_tra_thuc_te > ngay_hen_tra')
                                          ->whereBetween('ngay_tra_thuc_te', [$fromDate, $toDate]);
                            });
                    });
            })
            ->orderBy('ngay_hen_tra', 'desc')
            ->paginate(20);

        return view('admin.reports.inventory.late-return', compact(
            'totalLateReturns',
            'currentOverdue',
            'readerStats',
            'departmentStats',
            'overdueDaysStats',
            'monthlyStats',
            'lateReturns',
            'fromDate',
            'toDate'
        ));
    }
 
}

