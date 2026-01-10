<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Book;
use App\Models\Reader;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowDashboardController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->get('date_range', '30'); // Mặc định 30 ngày
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        
        return view('admin.borrows.dashboard', compact(
            'stats', 
            'timeStats', 
            'topBooks', 
            'topReaders', 
            'statusStats', 
            'overdueStats',
            'dateRange'
        ));
    }

    private function getOverviewStats($startDate, $endDate)
    {

    private function getTimeStats($startDate, $endDate)
    {
        $borrows = Borrow::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $returns = Borrow::whereBetween('ngay_tra_thuc_te', [$startDate, $endDate])
            ->whereNotNull('ngay_tra_thuc_te')
            ->selectRaw('DATE(ngay_tra_thuc_te) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'borrows' => $borrows,
            'returns' => $returns,
        ];
    }

    
    }

    private function getTopReaders($startDate, $endDate, $limit = 10)
    {
        
    }

    private function getStatusStats()
    {
        return [
            'dang_muon' => Borrow::where('trang_thai', 'Dang muon')->count(),
            'da_tra' => Borrow::where('trang_thai', 'Da tra')->count(),
            'qua_han' => Borrow::where('trang_thai', 'Qua han')->count(),
            'mat_sach' => Borrow::where('trang_thai', 'Mat sach')->count(),
        ];
    }

    private function getOverdueStats()
    {
        $overdueBorrows = Borrow::where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', now()->toDateString())
            ->with(['reader', 'book'])
            ->get();

        return [
            'count' => $overdueBorrows->count(),
            'borrows' => $overdueBorrows->take(10), // Chỉ lấy 10 phiếu đầu tiên
            'total_days' => $overdueBorrows->sum(function($borrow) {
                return now()->diffInDays($borrow->ngay_hen_tra);
            }),
        ];
    }

    public function export(Request $request)
    {
        
            // Header
            fputcsv($file, [
                'ID', 'Độc giả', 'Sách', 'Ngày mượn', 'Hạn trả', 
                'Ngày trả', 'Trạng thái', 'Số lần gia hạn', 'Thủ thư'
            ]);

            // Data
            
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


