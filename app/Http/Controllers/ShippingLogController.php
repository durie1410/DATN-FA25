<?php

namespace App\Http\Controllers;

use App\Models\ShippingLog;
use App\Models\Borrow;
use App\Models\BorrowItem;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShippingLogController extends Controller
{
    /**
     * Hiển thị tất cả shipping logs
     */
    public function index(Request $request)
    {
        $query = ShippingLog::with(['borrow.reader', 'borrow.items.book'])
            ->orderBy('id', 'desc');

        // Tìm kiếm theo mã đơn hàng hoặc tên người đặt
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('borrow', function($q2) use ($search) {
                      $q2->where('id', 'like', "%{$search}%")
                         ->orWhereHas('reader', function($q3) use ($search) {
                             $q3->where('name', 'like', "%{$search}%");
                         });
                  });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $logs = $query->paginate(20);

        // Đếm số lượng theo từng trạng thái
        $statusCounts = [
            'all' => ShippingLog::count(),
            'cho_xu_ly' => ShippingLog::where('status', 'cho_xu_ly')->count(),
            'dang_chuan_bi' => ShippingLog::where('status', 'dang_chuan_bi')->count(),
            'dang_giao' => ShippingLog::where('status', 'dang_giao')->count(),
            'da_giao_thanh_cong' => ShippingLog::where('status', 'da_giao_thanh_cong')->count(),
            'giao_that_bai' => ShippingLog::where('status', 'giao_that_bai')->count(),
            'tra_lai_sach' => ShippingLog::where('status', 'tra_lai_sach')->count(),
            'dang_gui_lai' => ShippingLog::where('status', 'dang_gui_lai')->count(),
            'da_nhan_hang' => ShippingLog::where('status', 'da_nhan_hang')->count(),
            'dang_kiem_tra' => ShippingLog::where('status', 'dang_kiem_tra')->count(),
            'thanh_toan_coc' => ShippingLog::where('status', 'thanh_toan_coc')->count(),
            'hoan_thanh' => ShippingLog::where('status', 'hoan_thanh')->count(),
            'da_huy' => ShippingLog::where('status', 'da_huy')->count(),
        ];

        return view('admin.shipping_logs.index', compact('logs', 'statusCounts'));
    }

    /**
     * Hiển thị shipping logs theo 1 Borrow
     */
    public function showByBorrow($borrowId)
    {
        $borrow = Borrow::with([
            'items.book',
            'shippingLogs.item.book'
        ])->findOrFail($borrowId);

        return view('admin.shipping_logs.by_borrow', compact('borrow'));
    }

    /**
     * Hiển thị shipping logs theo 1 BorrowItem
     */
public function show($id)
{
    // load shipping log cùng với borrow -> reader, items -> book, payments
    $log = \App\Models\ShippingLog::with([
        'borrow.reader',
        'borrow.items.book',
        'borrow.payments'           // nếu Borrow có payments relation
    ])->findOrFail($id);

    return view('admin.shipping_logs.show', compact('log'));
}

    /**
     * Thêm Shipping Log mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrow_id'      => 'required|exists:borrows,id',
            'borrow_item_id' => 'required|exists:borrow_items,id',
            'status'         => 'required|string',
            'shipper_note'   => 'nullable|string',
            'receiver_note'  => 'nullable|string',
            'proof_image'    => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        // Lưu ảnh minh chứng
        if ($request->hasFile('proof_image')) {
            try {
                $result = FileUploadService::uploadImage(
                    $request->file('proof_image'),
                    'shipping_proofs', // Directory name
                    [
                        'max_size' => 2048, // 2MB
                        'resize' => true,
                        'width' => 800,
                        'height' => 800,
                        'disk' => 'public',
                    ]
                );
                $data['proof_image'] = $result['path'];
            } catch (\Exception $e) {
                \Log::error('Upload proof image error:', ['message' => $e->getMessage()]);
                return redirect()->back()
                    ->withErrors(['proof_image' => $e->getMessage()])
                    ->withInput();
            }
        }

        ShippingLog::create($data);

        return back()->with('success', 'Đã thêm log giao hàng.');
    }

    /**
     * Cập nhật trạng thái giao hàng
     */
public function updateStatus(Request $request, $id)
{
    $log = ShippingLog::findOrFail($id);

    // Validate với 12 trạng thái
    $request->validate([
        'status'        => 'required|string|in:cho_xu_ly,dang_chuan_bi,dang_giao,da_giao_thanh_cong,giao_that_bai,tra_lai_sach,dang_gui_lai,da_nhan_hang,dang_kiem_tra,thanh_toan_coc,hoan_thanh,da_huy',
        'tinh_trang_sach' => 'nullable|in:binh_thuong,hong_nhe,hong_nang,mat_sach',
        'ghi_chu_kiem_tra' => 'nullable|string',
        'ghi_chu_hoan_coc' => 'nullable|string',
        'receiver_note' => 'nullable|string',
        'delivered_at'  => 'nullable|date',
        'proof_image'   => 'nullable|image|max:2048',
    ]);

    // Nếu trạng thái là 'da_giao', có thể yêu cầu ảnh minh chứng (tùy chọn)
    // if ($request->status === 'da_giao' && !$request->hasFile('proof_image') && !$log->proof_image) {
    //     return back()->withErrors(['proof_image' => 'Phải tải ảnh minh chứng trước khi đánh dấu đã giao.']);
    // }

    // Nếu có file, lưu vào storage
    if ($request->hasFile('proof_image')) {
        try {
            // Xóa ảnh cũ nếu có
            if ($log->proof_image && Storage::disk('public')->exists($log->proof_image)) {
                FileUploadService::deleteFile($log->proof_image, 'public');
            }
            
            // Upload ảnh mới sử dụng FileUploadService
            $result = FileUploadService::uploadImage(
                $request->file('proof_image'),
                'shipping_proofs', // Directory name - không được rỗng
                [
                    'max_size' => 2048, // 2MB
                    'resize' => true,
                    'width' => 800,
                    'height' => 800,
                    'disk' => 'public',
                ]
            );
            $log->proof_image = $result['path'];
        } catch (\Exception $e) {
            \Log::error('Upload proof image error:', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->withErrors(['proof_image' => $e->getMessage()])
                ->withInput();
        }
    }

    // Cập nhật các trường
    $log->status = $request->status;
    
    // Xử lý theo từng trạng thái
    switch ($request->status) {
        case 'dang_chuan_bi':
            $log->ngay_chuan_bi = now();
            $log->nguoi_chuan_bi_id = auth()->id();
            break;
            
        case 'dang_giao':
            $log->ngay_bat_dau_giao = now();
            break;
            
        case 'da_giao_thanh_cong':
            $log->ngay_giao_thanh_cong = now();
            $log->delivered_at = now();
            // Cập nhật borrow items
            if ($log->borrow) {
                $log->borrow->items()->update(['trang_thai' => 'Dang muon']);
                $log->borrow->update(['trang_thai' => 'Dang muon']);
            }
            break;
            
        case 'tra_lai_sach':
            $log->ngay_bat_dau_tra = now();
            break;
            
        case 'da_nhan_hang':
            $log->ngay_nhan_tra = now();
            break;
            
        case 'dang_kiem_tra':
            $log->ngay_kiem_tra = now();
            $log->nguoi_kiem_tra_id = auth()->id();
            break;
            
        case 'thanh_toan_coc':
            if (!$request->filled('tinh_trang_sach')) {
                return back()->withErrors(['tinh_trang_sach' => 'Vui lòng chọn tình trạng sách'])->withInput();
            }
            
            $log->tinh_trang_sach = $request->tinh_trang_sach;
            $log->ngay_hoan_coc = now();
            $log->nguoi_hoan_coc_id = auth()->id();
            
            // Tính phí hỏng sách
            $phiHong = $this->calculateDamageFee($log, $request->tinh_trang_sach);
            $log->phi_hong_sach = $phiHong;
            
            // Tính tiền cọc hoàn trả
            $tienCoc = $log->borrow ? $log->borrow->tien_coc : 0;
            $log->tien_coc_hoan_tra = max(0, $tienCoc - $phiHong);
            
            if ($request->filled('ghi_chu_kiem_tra')) {
                $log->ghi_chu_kiem_tra = $request->ghi_chu_kiem_tra;
            }
            if ($request->filled('ghi_chu_hoan_coc')) {
                $log->ghi_chu_hoan_coc = $request->ghi_chu_hoan_coc;
            }
            break;
            
        case 'hoan_thanh':
            if ($log->borrow) {
                $log->borrow->items()->update(['trang_thai' => 'Da tra', 'ngay_tra_thuc_te' => now()]);
                $log->borrow->update(['trang_thai' => 'Da tra']);
            }
            break;
            
        case 'da_huy':
            if ($log->borrow) {
                $log->borrow->items()->update(['trang_thai' => 'Da huy']);
                $log->borrow->update(['trang_thai' => 'Da huy']);
            }
            break;
    }
    
    if ($request->filled('receiver_note')) {
        $log->receiver_note = $request->receiver_note;
    }
    
    if ($request->filled('delivered_at')) {
        $log->delivered_at = $request->delivered_at;
    }
    
    $log->save();

    // Nếu trạng thái là đã giao, cập nhật tất cả sách trong borrow_items
    if ($request->status === 'da_giao') {
        $borrow = $log->borrow;
        if ($borrow) {
            $borrow->items()->update(['trang_thai' => 'Dang muon']);
            // Cập nhật trạng thái của borrow
            $borrow->trang_thai = 'Dang muon';
            $borrow->save();
        }
    }

    return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
}

    /**
     * Tính phí hỏng sách
     */
    protected function calculateDamageFee(ShippingLog $log, $condition)
    {
        if ($condition === 'binh_thuong') {
            return 0;
        }

        $totalBookValue = 0;
        if ($log->borrow) {
            foreach ($log->borrow->items as $item) {
                if ($item->book) {
                    $totalBookValue += $item->book->gia ?? 0;
                }
            }
        }

        switch ($condition) {
            case 'hong_nhe':
                return $totalBookValue * 0.1; // 10%
            case 'hong_nang':
                return $totalBookValue * 0.5; // 50%
            case 'mat_sach':
                return $totalBookValue; // 100%
            default:
                return 0;
        }
    }

    /**
     * Xoá log
     */
    public function destroy($id)
    {
        $log = ShippingLog::findOrFail($id);
        $log->delete();

        return back()->with('success', 'Đã xoá shipping log.');
    }
}

