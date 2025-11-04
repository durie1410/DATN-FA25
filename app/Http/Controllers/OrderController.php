<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PurchasableBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị trang checkout
     */
    public function checkout()
    {
        $cart = $this->getCurrentCart();
        $cartItems = $cart->items()->with('purchasableBook')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        return view('orders.checkout', compact('cart', 'cartItems'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = $this->getCurrentCart();
        $cartItems = $cart->items()->with('purchasableBook')->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng của bạn đang trống'
            ], 400);
        }

        // Kiểm tra số lượng tồn kho trước khi đặt hàng
        foreach ($cartItems as $item) {
            $book = $item->purchasableBook;
            if (!$book->isInStock() || $book->so_luong_ton < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Sách '{$book->ten_sach}' không đủ hàng trong kho"
                ], 400);
            }
        }

        DB::beginTransaction();
        
        try {
            // Tạo đơn hàng
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'session_id' => Session::getId(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $cart->total_amount,
                'tax_amount' => 0, // Miễn phí thuế cho sách điện tử
                'shipping_amount' => 0, // Miễn phí vận chuyển cho sách điện tử
                'total_amount' => $cart->total_amount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Tạo order items và cập nhật số lượng tồn kho
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'purchasable_book_id' => $item->purchasable_book_id,
                    'book_title' => $item->purchasableBook->ten_sach,
                    'book_author' => $item->purchasableBook->tac_gia,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price,
                ]);

                // Giảm số lượng tồn kho và tăng số lượng bán
                $item->purchasableBook->decreaseStock($item->quantity);
                $item->purchasableBook->incrementSales();
            }

            // Xóa giỏ hàng
            $cart->items()->delete();
            $cart->update(['total_amount' => 0, 'total_items' => 0]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công!',
                'order_number' => $order->order_number,
                'redirect_url' => route('orders.show', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        // Kiểm tra quyền truy cập
        if (!$this->canAccessOrder($order)) {
            abort(403, 'Bạn không có quyền xem đơn hàng này');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Hiển thị danh sách đơn hàng của user
     */
    public function index()
    {
        $query = Order::with('items');
        
        if (Auth::check()) {
            $query->forUser(Auth::id());
        } else {
            $query->forSession(Session::getId());
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Lấy giỏ hàng hiện tại
     */
    private function getCurrentCart()
    {
        if (Auth::check()) {
            return Cart::getOrCreateForUser(Auth::id());
        } else {
            $sessionId = Session::getId();
            return Cart::getOrCreateForSession($sessionId);
        }
    }

    /**
     * Kiểm tra quyền truy cập đơn hàng
     */
    private function canAccessOrder($order)
    {
        if (Auth::check()) {
            return $order->user_id === Auth::id();
        } else {
            return $order->session_id === Session::getId();
        }
    }
}
