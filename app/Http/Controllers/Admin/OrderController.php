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
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        try {
            DB::beginTransaction();

            if ($request->filled('status')) {
                $order->status = $request->status;
                
                // Tự động cập nhật trạng thái thanh toán thành "Đã thanh toán" 
                // khi trạng thái đơn hàng được cập nhật thành "Đã giao thành công"
                if ($request->status === 'delivered' && !$request->filled('payment_status')) {
                    // Chỉ tự động cập nhật nếu payment_status chưa được chỉ định trong request
                    // và trạng thái thanh toán hiện tại chưa phải là "đã thanh toán"
                    if ($order->payment_status !== 'paid') {
                        $order->payment_status = 'paid';
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

            if ($request->filled('payment_status')) {
                $order->payment_status = $request->payment_status;
            }

            $order->save();

            DB::commit();

            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
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

