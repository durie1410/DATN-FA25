<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Borrow;
use App\Models\BorrowItem;
use App\Models\Fine;
use App\Models\Reader;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy thống kê tổng quan theo đúng như trong ảnh
        $totalBooks = Book::count();
        $totalBorrowingReaders = Borrow::where('trang_thai', 'Dang muon')->count();
        
        // Thống kê bổ sung
        $totalReservations = 0; // Reservation model đã bị xóa
        
        // Thống kê theo thể loại
        $categoryStats = Category::withCount('books')->get();
        $totalCategories = Category::count();
        
        // Tổng hợp tiền - Doanh thu từ MƯỢN SÁCH
        // Tính tổng tiền từ các phiếu mượn (có thể lọc theo trạng thái đã hoàn thành hoặc tất cả)
        $totalRevenueFromBorrows = Borrow::sum('tong_tien');
        $monthlyRevenueFromBorrows = Borrow::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('tong_tien');
        $todayRevenueFromBorrows = Borrow::whereDate('created_at', Carbon::today())
            ->sum('tong_tien');
        
        // Tổng hợp tiền - Doanh thu từ ĐẶT TRƯỚC/MUA SÁCH (Orders)
        // Chỉ tính các đơn hàng đã thanh toán
        $totalRevenueFromOrders = Order::where('payment_status', 'paid')->sum('total_amount');
        $monthlyRevenueFromOrders = Order::where('payment_status', 'paid')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');
        $todayRevenueFromOrders = Order::where('payment_status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');
        
        // TỔNG HỢP - Từ cả mượn sách và đặt trước
        $totalRevenue = $totalRevenueFromBorrows + $totalRevenueFromOrders;
        $monthlyRevenue = $monthlyRevenueFromBorrows + $monthlyRevenueFromOrders;
        $todayRevenue = $todayRevenueFromBorrows + $todayRevenueFromOrders;
        
        // Tính doanh thu tháng trước để so sánh
        $lastMonthRevenueFromBorrows = Borrow::whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('tong_tien');
        $lastMonthRevenueFromOrders = Order::where('payment_status', 'paid')
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total_amount');
        $lastMonthRevenue = $lastMonthRevenueFromBorrows + $lastMonthRevenueFromOrders;
        
        // Tính phần trăm tăng/giảm so với tháng trước
        $revenueChangePercent = 0;
        if ($lastMonthRevenue > 0) {
            $revenueChangePercent = (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } elseif ($monthlyRevenue > 0) {
            $revenueChangePercent = 100; // Tăng 100% nếu tháng trước = 0
        }
        
        // Tổng hợp tiền phạt
        $totalFinesPaid = Fine::where('status', 'paid')->sum('amount');
        $totalFinesPending = Fine::where('status', 'pending')->sum('amount');
        $totalFinesOverdue = Fine::overdue()->sum('amount');
        
        // Thống kê doanh thu theo tháng (12 tháng gần nhất)
        $monthlyRevenueStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            
            $revenueFromBorrows = Borrow::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('tong_tien');
            
            $revenueFromOrders = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_amount');
            
            $monthlyRevenueStats[] = [
                'label' => 'T' . $date->month,
                'month' => $date->month,
                'year' => $year,
                'revenue' => $revenueFromBorrows + $revenueFromOrders
            ];
        }
        
        // Thống kê mượn sách theo tháng (12 tháng gần nhất)
        // Hiển thị số phiếu mượn đang ở trạng thái "Dang muon" được tạo trong từng tháng
        $monthlyBorrowStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            
            // Đếm số phiếu mượn được tạo trong tháng này và VẪN đang ở trạng thái "Dang muon"
            $borrowCount = Borrow::whereYear('ngay_muon', $year)
                ->whereMonth('ngay_muon', $month)
                ->where('trang_thai', 'Dang muon')
                ->count();
            
            $monthlyBorrowStats[] = [
                'label' => 'T' . $date->month,
                'month' => $date->month,
                'year' => $year,
                'count' => $borrowCount
            ];
        }
        
        // Lấy hoạt động gần đây
        $recentActivities = $this->getRecentActivities();
        
        return view('admin.dashboard', compact(
            'totalBooks',
            'totalBorrowingReaders',
            'totalReservations',
            'categoryStats',
            'totalCategories',
            'totalRevenue',
            'monthlyRevenue',
            'todayRevenue',
            'revenueChangePercent',
            'totalRevenueFromBorrows',
            'monthlyRevenueFromBorrows',
            'todayRevenueFromBorrows',
            'totalRevenueFromOrders',
            'monthlyRevenueFromOrders',
            'todayRevenueFromOrders',
            'totalFinesPaid',
            'totalFinesPending',
            'totalFinesOverdue',
            'monthlyRevenueStats',
            'monthlyBorrowStats',
            'recentActivities'
        ));
    }
    
    /**
     * Lấy danh sách hoạt động gần đây
     */
    private function getRecentActivities()
    {
        $activities = [];
        
        // 1. Sách mới được thêm vào hệ thống
        $newBook = Book::orderBy('created_at', 'desc')->first();
        if ($newBook) {
            $activities[] = [
                'type' => 'book_added',
                'icon' => 'fas fa-book',
                'icon_color' => 'var(--primary-color)',
                'bg_color' => 'rgba(0, 255, 153, 0.2)',
                'text_color' => 'var(--primary-color)',
                'title' => 'Sách mới đã được thêm vào hệ thống',
                'description' => $newBook->ten_sach,
                'time' => $newBook->created_at,
                'action_text' => 'Thêm sách',
                'action_url' => route('admin.books.index'),
            ];
        }
        
        // 2. Độc giả mới đã đăng ký
        $newReader = Reader::orderBy('created_at', 'desc')->first();
        if ($newReader) {
            $activities[] = [
                'type' => 'reader_registered',
                'icon' => 'fas fa-user-plus',
                'icon_color' => '#28a745',
                'bg_color' => 'rgba(40, 167, 69, 0.2)',
                'text_color' => '#28a745',
                'title' => 'Độc giả mới đã đăng ký',
                'description' => $newReader->ho_ten,
                'time' => $newReader->created_at,
                'action_text' => 'Đăng ký',
                'action_url' => route('admin.readers.create'),
            ];
        }
        
        // 3. Sách đã được trả về thư viện
        $returnedItem = BorrowItem::where(function($query) {
                $query->whereNotNull('ngay_tra_thuc_te')
                      ->orWhere('trang_thai', '!=', 'Dang muon');
            })
            ->orderBy('updated_at', 'desc')
            ->with('book')
            ->first();
        if ($returnedItem && $returnedItem->book) {
            $activities[] = [
                'type' => 'book_returned',
                'icon' => 'fas fa-exchange-alt',
                'icon_color' => 'var(--secondary-color)',
                'bg_color' => 'rgba(255, 221, 0, 0.2)',
                'text_color' => 'var(--secondary-color)',
                'title' => 'Sách đã được trả về thư viện',
                'description' => $returnedItem->book->ten_sach,
                'time' => $returnedItem->updated_at,
                'action_text' => 'Trả sách',
                'action_url' => route('admin.borrows.index'),
            ];
        }
        
        // 4. Phát hiện sách quá hạn
        $overdueItem = BorrowItem::where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', now())
            ->orderBy('ngay_hen_tra', 'asc')
            ->with('book')
            ->first();
        if ($overdueItem && $overdueItem->book) {
            $activities[] = [
                'type' => 'book_overdue',
                'icon' => 'fas fa-exclamation-circle',
                'icon_color' => '#ff6b6b',
                'bg_color' => 'rgba(255, 107, 107, 0.2)',
                'text_color' => '#ff6b6b',
                'title' => 'Phát hiện sách quá hạn',
                'description' => $overdueItem->book->ten_sach,
                'time' => $overdueItem->ngay_hen_tra,
                'action_text' => 'Cảnh báo',
                'action_url' => route('admin.borrows.index'),
            ];
        }
        
        // Sắp xếp theo thời gian (mới nhất trước)
        usort($activities, function($a, $b) {
            return $b['time']->timestamp <=> $a['time']->timestamp;
        });
        
        // Chỉ lấy 4 hoạt động gần nhất
        return array_slice($activities, 0, 4);
    }
}
