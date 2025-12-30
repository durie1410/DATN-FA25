<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PurchasableBook;
use App\Models\Book;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Tìm kiếm theo mã đơn hàng, tên khách hàng, email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $status = $request->status;
            // Xử lý các trạng thái đặc biệt
            if ($status === 'confirmed') {
                $query->whereIn('status', ['confirmed', 'processing']);
            } elseif ($status === 'shipping') {
                $query->whereIn('status', ['shipping', 'shipped']);
            } else {
                $validStatuses = ['pending', 'processing', 'preparing', 'packing', 'sent_to_post_office', 'shipped', 'delivered', 'delivery_failed', 'cancelled'];
                if (in_array($status, $validStatuses)) {
                    $query->where('status', $status);
                }
            }
        }

        // Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Lọc theo ngày
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Thống kê số lượng đơn hàng theo từng trạng thái
        $stats = [
            'total' => Order::count(),
            'pending' => Order::whereIn('status', ['pending'])->count(),
            'confirmed' => Order::whereIn('status', ['confirmed', 'processing'])->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'packing' => Order::where('status', 'packing')->count(),
            'sent_to_post_office' => Order::where('status', 'sent_to_post_office')->count(),
            'shipping' => Order::whereIn('status', ['shipping', 'shipped'])->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'delivery_failed' => Order::where('status', 'delivery_failed')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.purchasableBook'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Hiển thị form chỉnh sửa đơn hàng
     */
    public function edit($id)
    {
        $order = Order::with(['user', 'items.purchasableBook'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:pending,confirmed,processing,preparing,packing,sent_to_post_office,shipping,shipped,delivered,delivery_failed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'payment_method' => 'nullable|in:cash_on_delivery,bank_transfer,momo,vnpay',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        try {
            DB::beginTransaction();

            // Cập nhật payment_method trước (nếu có)
            if ($request->filled('payment_method')) {
                $order->payment_method = $request->payment_method;
                
                // Tự động cập nhật payment_status dựa trên phương thức thanh toán
                if (!$request->filled('payment_status')) {
                    if (in_array($request->payment_method, ['vnpay', 'momo', 'bank_transfer'])) {
                        // Online payment -> Đã thanh toán
                        $order->payment_status = 'paid';
                    } elseif ($request->payment_method === 'cash_on_delivery') {
                        // COD: Tùy thuộc vào trạng thái giao hàng
                        if (in_array($order->status, ['delivered'])) {
                            $order->payment_status = 'paid';
                        } elseif (in_array($order->status, ['delivery_failed', 'cancelled'])) {
                            $order->payment_status = 'pending';
                        } else {
                            // Đơn hàng chưa giao -> Chưa thanh toán
                            $order->payment_status = 'pending';
                        }
                    }
                }
            }

            if ($request->filled('status')) {
                $order->status = $request->status;
                
                // Logic tự động cập nhật trạng thái thanh toán dựa trên phương thức và trạng thái đơn
                if (!$request->filled('payment_status')) {
                    // COD: Giao hàng thành công -> Đã thanh toán
                    if ($request->status === 'delivered' && $order->payment_method === 'cash_on_delivery') {
                        $order->payment_status = 'paid';
                    }
                    
                    // COD: Giao hàng thất bại -> Chưa thanh toán
                    if ($request->status === 'delivery_failed' && $order->payment_method === 'cash_on_delivery') {
                        $order->payment_status = 'pending';
                    }
                }
                
                // Xử lý khi admin hủy đơn hàng (chuyển từ trạng thái khác sang 'cancelled')
                if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                    // Hoàn lại số lượng tồn kho cho các sản phẩm trong đơn hàng
                    foreach ($order->items as $item) {
                        $purchasableBook = PurchasableBook::find($item->purchasable_book_id);
                        if ($purchasableBook) {
                            // Hoàn lại số lượng cho PurchasableBook
                            $purchasableBook->increaseStock($item->quantity);
                            
                            // Giảm số lượng đã bán (so_luong_ban) khi hủy đơn
                            // Đảm bảo so_luong_ban không bị âm
                            if ($purchasableBook->so_luong_ban >= $item->quantity) {
                                $purchasableBook->decrement('so_luong_ban', $item->quantity);
                            } else {
                                // Nếu so_luong_ban nhỏ hơn số lượng hủy, đặt về 0
                                $purchasableBook->update(['so_luong_ban' => 0]);
                            }
                            
                            // Hoàn lại số lượng cho Book và inventories
                            $book = Book::where('ten_sach', $purchasableBook->ten_sach)
                                ->first();
                            
                            if ($book) {
                                // Hoàn lại inventories từ 'Thanh ly' về 'Co san' nếu có
                                $soldInventories = Inventory::where('book_id', $book->id)
                                    ->where('storage_type', 'Kho')
                                    ->where('status', 'Thanh ly')
                                    ->limit($item->quantity)
                                    ->get();
                                
                                $inventoryCount = $soldInventories->count();
                                foreach ($soldInventories as $inventory) {
                                    $inventory->update(['status' => 'Co san']);
                                }
                                
                                // Tăng so_luong trong bảng books cho phần còn lại (nếu có)
                                $remainingQuantity = $item->quantity - $inventoryCount;
                                if ($remainingQuantity > 0) {
                                    $book->increment('so_luong', $remainingQuantity);
                                }
                            }
                        }
                    }
                }
            }

            // Cho phép admin ghi đè thủ công nếu cần
            if ($request->filled('payment_status')) {
                $order->payment_status = $request->payment_status;
            }

            $order->save();

            DB::commit();

            // Tạo thông báo chi tiết
            $message = 'Cập nhật đơn hàng thành công!';
            
            // Thêm thông tin về cập nhật tự động
            if ($request->filled('payment_method') && in_array($request->payment_method, ['vnpay', 'momo', 'bank_transfer'])) {
                $message .= ' Trạng thái thanh toán đã tự động chuyển sang "Đã thanh toán" do sử dụng phương thức online.';
            } elseif ($request->filled('status')) {
                if ($request->status === 'delivered' && $order->payment_method === 'cash_on_delivery' && !$request->filled('payment_status')) {
                    $message .= ' Trạng thái thanh toán đã tự động chuyển sang "Đã thanh toán" do giao hàng thành công (COD).';
                } elseif ($request->status === 'delivery_failed' && $order->payment_method === 'cash_on_delivery' && !$request->filled('payment_status')) {
                    $message .= ' Trạng thái thanh toán đã tự động chuyển về "Chưa thanh toán" do giao hàng thất bại (COD).';
                }
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Admin order update failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage());
        }
    }
}

