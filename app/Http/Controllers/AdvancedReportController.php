<?php

namespace App\Http\Controllers;

use App\Models\ReportTemplate;
use App\Models\Borrow;
use App\Models\Book;
use App\Models\Reader;
use App\Models\Fine;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AdvancedReportController extends Controller
{
    public function index()
    {
        $templates = ReportTemplate::active()
            ->where(function($query) {
                $query->where('is_public', true)
                      ->orWhere('created_by', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.advanced.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.reports.advanced.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'columns' => 'required|array',
            'filters' => 'nullable|array',
            'group_by' => 'nullable|array',
            'order_by' => 'nullable|array',
            'is_public' => 'boolean',
        ]);

        ReportTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'columns' => $request->columns,
            'filters' => $request->filters,
            'group_by' => $request->group_by,
            'order_by' => $request->order_by,
            'is_public' => $request->is_public ?? false,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.reports.advanced.index')
            ->with('success', 'Template báo cáo đã được tạo thành công!');
    }

    public function show($id)
    {
        $template = ReportTemplate::findOrFail($id);
        
        // Kiểm tra quyền truy cập
        if (!$template->is_public && $template->created_by !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem template này.');
        }

        return view('admin.reports.advanced.show', compact('template'));
    }

    public function generate(Request $request, $id)
    {
        $template = ReportTemplate::findOrFail($id);
        
        // Kiểm tra quyền truy cập
        if (!$template->is_public && $template->created_by !== Auth::id()) {
            abort(403, 'Bạn không có quyền sử dụng template này.');
        }

        $filters = $request->all();
        $data = $this->getReportData($template, $filters);

        if ($request->has('export')) {
            return $this->exportReport($template, $data, $filters);
        }

        return view('admin.reports.advanced.result', compact('template', 'data', 'filters'));
    }

    private function getReportData($template, $filters)
    {
        switch ($template->type) {
            case 'borrows':
                return $this->getBorrowsData($template, $filters);
            case 'books':
                return $this->getBooksData($template, $filters);
            case 'readers':
                return $this->getReadersData($template, $filters);
            case 'fines':
                return $this->getFinesData($template, $filters);
            case 'reservations':
                return $this->getReservationsData($template, $filters);
            case 'statistics':
                return $this->getStatisticsData($template, $filters);
            default:
                return [];
        }
    }

    private function getBorrowsData($template, $filters)
    {
        $query = Borrow::with(['book', 'reader', 'librarian']);

        // Áp dụng filters
        if (isset($filters['from_date']) && $filters['from_date']) {
            $query->where('ngay_muon', '>=', $filters['from_date']);
        }
        if (isset($filters['to_date']) && $filters['to_date']) {
            $query->where('ngay_muon', '<=', $filters['to_date']);
        }
        if (isset($filters['status']) && $filters['status']) {
            $query->where('trang_thai', $filters['status']);
        }
        if (isset($filters['reader_id']) && $filters['reader_id']) {
            $query->where('reader_id', $filters['reader_id']);
        }

        // Áp dụng group_by
        if ($template->group_by) {
            foreach ($template->group_by as $group) {
                $query->groupBy($group);
            }
        }

        // Áp dụng order_by
        if ($template->order_by) {
            foreach ($template->order_by as $order) {
                $query->orderBy($order['column'], $order['direction'] ?? 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    private function getBooksData($template, $filters)
    {
        $query = Book::with(['category', 'reviews', 'borrows']);

        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }
        if (isset($filters['year_from']) && $filters['year_from']) {
            $query->where('nam_xuat_ban', '>=', $filters['year_from']);
        }
        if (isset($filters['year_to']) && $filters['year_to']) {
            $query->where('nam_xuat_ban', '<=', $filters['year_to']);
        }

        if ($template->group_by) {
            foreach ($template->group_by as $group) {
                $query->groupBy($group);
            }
        }

        if ($template->order_by) {
            foreach ($template->order_by as $order) {
                $query->orderBy($order['column'], $order['direction'] ?? 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    private function getReadersData($template, $filters)
    {
        $query = Reader::with(['borrows', 'fines']);

        if (isset($filters['status']) && $filters['status']) {
            $query->where('trang_thai', $filters['status']);
        }
        if (isset($filters['gender']) && $filters['gender']) {
            $query->where('gioi_tinh', $filters['gender']);
        }

        if ($template->group_by) {
            foreach ($template->group_by as $group) {
                $query->groupBy($group);
            }
        }

        if ($template->order_by) {
            foreach ($template->order_by as $order) {
                $query->orderBy($order['column'], $order['direction'] ?? 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    private function getFinesData($template, $filters)
    {
        $query = Fine::with(['borrow.book', 'reader', 'creator']);

        if (isset($filters['from_date']) && $filters['from_date']) {
            $query->where('created_at', '>=', $filters['from_date']);
        }
        if (isset($filters['to_date']) && $filters['to_date']) {
            $query->where('created_at', '<=', $filters['to_date']);
        }
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }

        if ($template->group_by) {
            foreach ($template->group_by as $group) {
                $query->groupBy($group);
            }
        }

        if ($template->order_by) {
            foreach ($template->order_by as $order) {
                $query->orderBy($order['column'], $order['direction'] ?? 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    private function getReservationsData($template, $filters)
    {
        $query = Reservation::with(['book', 'reader', 'user']);

        if (isset($filters['from_date']) && $filters['from_date']) {
            $query->where('reservation_date', '>=', $filters['from_date']);
        }
        if (isset($filters['to_date']) && $filters['to_date']) {
            $query->where('reservation_date', '<=', $filters['to_date']);
        }
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if ($template->group_by) {
            foreach ($template->group_by as $group) {
                $query->groupBy($group);
            }
        }

        if ($template->order_by) {
            foreach ($template->order_by as $order) {
                $query->orderBy($order['column'], $order['direction'] ?? 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    private function getStatisticsData($template, $filters)
    {
        $fromDate = $filters['from_date'] ?? Carbon::now()->startOfMonth();
        $toDate = $filters['to_date'] ?? Carbon::now()->endOfMonth();

        return [
            'total_books' => Book::count(),
            'total_readers' => Reader::count(),
            'total_borrows' => Borrow::whereBetween('ngay_muon', [$fromDate, $toDate])->count(),
            'total_fines' => Fine::whereBetween('created_at', [$fromDate, $toDate])->sum('amount'),
            'total_reservations' => Reservation::whereBetween('reservation_date', [$fromDate, $toDate])->count(),
            'overdue_borrows' => Borrow::where('trang_thai', 'Dang muon')
                ->where('ngay_hen_tra', '<', Carbon::today())
                ->count(),
            'pending_fines' => Fine::where('status', 'pending')->sum('amount'),
            'active_readers' => Reader::where('trang_thai', 'Hoat dong')->count(),
        ];
    }

    private function exportReport($template, $data, $filters)
    {
        $filename = $template->name . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new \App\Exports\ReportExport($template, $data), $filename);
    }

    public function dashboard()
    {
        $stats = [
            'total_templates' => ReportTemplate::count(),
            'my_templates' => ReportTemplate::where('created_by', Auth::id())->count(),
            'public_templates' => ReportTemplate::where('is_public', true)->count(),
            'recent_templates' => ReportTemplate::where('created_by', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('admin.reports.advanced.dashboard', compact('stats'));
    }

    public function destroy($id)
    {
        $template = ReportTemplate::findOrFail($id);
        
        if ($template->created_by !== Auth::id()) {
            abort(403, 'Bạn chỉ có thể xóa template của mình.');
        }

        $template->delete();

        return back()->with('success', 'Template đã được xóa thành công!');
    }
}