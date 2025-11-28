<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PurchasableBook;
use App\Models\Book;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị trang checkout
     */
    public function checkout(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để mua hàng!');
        }

        $cart = $this->getCurrentCart();
        
        // Xử lý trường hợp "Mua ngay" - có book_id và paper_quantity trong query
        if ($request->has('book_id') && $request->has('paper_quantity')) {
            try {
                $bookId = $request->book_id;
                $paperQuantity = (int) $request->paper_quantity;
                
                // Validate số lượng
                if ($paperQuantity < 1 || $paperQuantity > 10) {
                    return redirect()->back()->with('error', 'Số lượng sách không hợp lệ (1-10 cuốn)');
                }
                
                // Lấy hoặc tạo PurchasableBook
                $purchasableBook = $this->getOrCreatePurchasableBook($bookId, 'paper');
                
                // Kiểm tra tồn kho
                if (!$purchasableBook->isInStock()) {
                    return redirect()->back()->with('error', 'Sách này đã hết hàng');
                }
                
                if ($purchasableBook->so_luong_ton < $paperQuantity) {
                    return redirect()->back()->with('error', "Sách chỉ còn {$purchasableBook->so_luong_ton} bản trong kho");
                }
                
                // Thêm vào giỏ hàng
                CartItem::addOrUpdate($cart->id, $purchasableBook->id, $paperQuantity);
                
                // Lấy cart item vừa thêm (với relationship)
                $cartItem = $cart->items()
                    ->where('purchasable_book_id', $purchasableBook->id)
                    ->with('purchasableBook')
                    ->first();
                
                if ($cartItem) {
                    // Chỉ chọn item vừa thêm vào
                    $selectedItemIds = [$cartItem->id];
                    $cartItems = collect([$cartItem]);
                } else {
                    return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
                }
            } catch (\Exception $e) {
                \Log::error('Checkout buy now error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'request' => $request->all()
                ]);
                return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            }
        } else {
            // Xử lý trường hợp bình thường - từ giỏ hàng
            // Lấy danh sách item_id được chọn từ query parameter
            $selectedItemIds = [];
            if ($request->has('items') && !empty($request->items)) {
                $selectedItemIds = explode(',', $request->items);
                $selectedItemIds = array_filter(array_map('intval', $selectedItemIds));
            }
            
            // Nếu không có sản phẩm được chọn, lấy tất cả
            if (empty($selectedItemIds)) {
                $cartItems = $cart->items()->with('purchasableBook')->get();
            } else {
                // Chỉ lấy các sản phẩm được chọn
                $cartItems = $cart->items()
                    ->whereIn('id', $selectedItemIds)
                    ->with('purchasableBook')
                    ->get();
            }
        }
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán');
        }
        
        // Tính tổng tiền của các sản phẩm được chọn
        $selectedTotal = $cartItems->sum('total_price');
        
        // Lưu danh sách item_id được chọn vào session để sử dụng khi đặt hàng
        Session::put('checkout_selected_items', $selectedItemIds);

        return view('orders.checkout', compact('cart', 'cartItems', 'selectedTotal'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
    {
        // Log để debug
        \Log::info('OrderController@store called', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ]);
        
        // Đảm bảo chỉ xử lý POST request
        // Nếu là GET request, redirect về trang index
        if (!$request->isMethod('POST')) {
            \Log::warning('OrderController@store called with wrong method - redirecting to index', [
                'method' => $request->method(),
                'expected' => 'POST',
                'url' => $request->fullUrl()
            ]);
            
            // Nếu là GET request, redirect về trang orders.index
            if ($request->isMethod('GET')) {
                return redirect()->route('orders.index');
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed. Only POST requests are accepted.'
            ], 405);
        }
        
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_address' => 'nullable|string|max:500',
                'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
                'notes' => 'nullable|string|max:1000',
            ], [
                'customer_name.required' => 'Vui lòng nhập họ và tên',
                'customer_email.required' => 'Vui lòng nhập email',
                'customer_email.email' => 'Email không hợp lệ',
                'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
                'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        }

        $cart = $this->getCurrentCart();
        
        // Lấy danh sách item_id được chọn từ session (đã lưu khi vào trang checkout)
        $selectedItemIds = Session::get('checkout_selected_items', []);
        
        // Nếu không có sản phẩm được chọn, lấy tất cả
        if (empty($selectedItemIds)) {
            $cartItems = $cart->items()->with('purchasableBook')->get();
        } else {
            // Chỉ lấy các sản phẩm được chọn
            $cartItems = $cart->items()
                ->whereIn('id', $selectedItemIds)
                ->with('purchasableBook')
                ->get();
        }
        
        if ($cartItems->isEmpty()) {
            // Log để debug
            \Log::warning('OrderController@store: No items selected', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'user_id' => Auth::id(),
                'session_id' => Session::getId(),
                'selected_item_ids' => $selectedItemIds,
            ]);
            
            // Nếu là AJAX request, trả JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn ít nhất một sản phẩm để đặt hàng',
                    'redirect_url' => route('cart.index')
                ], 400);
            }
            
            // Nếu không phải AJAX, redirect về trang giỏ hàng
            return redirect()->route('cart.index')
                ->with('error', 'Vui lòng chọn ít nhất một sản phẩm để đặt hàng.');
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
        
        // Tính tổng tiền của các sản phẩm được chọn
        $selectedTotal = $cartItems->sum('total_price');

        DB::beginTransaction();
        
        try {
            // Xác định payment_status dựa trên payment_method
            $paymentStatus = 'pending';
            if ($request->payment_method === 'cash_on_delivery') {
                // Với COD, payment_status sẽ là 'pending' cho đến khi giao hàng
                $paymentStatus = 'pending';
            } elseif ($request->payment_method === 'bank_transfer') {
                // Với chuyển khoản, payment_status cũng là 'pending' cho đến khi xác nhận
                $paymentStatus = 'pending';
            }
            
            // Tạo đơn hàng
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'session_id' => Session::getId(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $selectedTotal,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'total_amount' => $selectedTotal,
                'status' => 'pending',
                'payment_status' => $paymentStatus,
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

                // Giảm số lượng tồn kho từ PurchasableBook
                $item->purchasableBook->decreaseStock($item->quantity);
                $item->purchasableBook->incrementSales();
                
                // Giảm số lượng từ bảng books và inventories
                // Tìm Book tương ứng với PurchasableBook (dựa trên tên sách)
                $book = Book::where('ten_sach', $item->purchasableBook->ten_sach)
                    ->first();
                
                if ($book) {
                    // Lấy số lượng cần giảm
                    $quantityToDecrease = $item->quantity;
                    
                    // Lấy các inventories có sẵn trong kho (storage_type = 'Kho' và status = 'Co san')
                    $availableInventories = Inventory::where('book_id', $book->id)
                        ->where('storage_type', 'Kho')
                        ->where('status', 'Co san')
                        ->limit($quantityToDecrease)
                        ->get();
                    
                    // Cập nhật status của inventories từ 'Co san' sang 'Thanh ly' (đã bán)
                    foreach ($availableInventories as $inventory) {
                        $inventory->update(['status' => 'Thanh ly']);
                    }
                    
                    // Giảm so_luong từ bảng books
                    // Nếu có inventories, chỉ giảm phần còn lại từ so_luong
                    $inventoryCount = $availableInventories->count();
                    $remainingQuantity = $quantityToDecrease - $inventoryCount;
                    
                    if ($remainingQuantity > 0) {
                        $book->decrement('so_luong', $remainingQuantity);
                    }
                    
                    // Đảm bảo so_luong không âm
                    if ($book->so_luong < 0) {
                        $book->update(['so_luong' => 0]);
                    }
                }
            }

            // Xóa các sản phẩm đã đặt hàng khỏi giỏ hàng
            if (!empty($selectedItemIds)) {
                $cart->items()->whereIn('id', $selectedItemIds)->delete();
            } else {
                // Nếu không có danh sách chọn, xóa tất cả (fallback)
                $cart->items()->delete();
            }
            
            // Tính lại tổng giỏ hàng
            $cart->recalculateTotals();
            
            // Xóa session chứa danh sách sản phẩm được chọn
            Session::forget('checkout_selected_items');

            DB::commit();
            
            // Log thành công
            \Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $order->user_id
            ]);

            // Nếu là AJAX request, trả JSON với redirect_url
            // Nếu không phải AJAX, redirect trực tiếp
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number,
                    'order_number' => $order->order_number,
                    'redirect_url' => route('orders.index')
                ], 200)->header('Content-Type', 'application/json')
                  ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
                  ->header('Pragma', 'no-cache')
                  ->header('Expires', '0');
            }
            
            // Redirect trực tiếp cho non-AJAX requests
            return redirect()->route('orders.index')
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log lỗi chi tiết
            \Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
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
    public function index(Request $request)
    {
        // Log để debug
        \Log::info('OrderController@index called', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'is_api' => $request->is('api/*'),
            'expects_json' => $request->expectsJson(),
            'wants_json' => $request->wantsJson(),
            'accept' => $request->header('Accept'),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        // Đảm bảo chỉ xử lý GET request
        if (!$request->isMethod('GET')) {
            \Log::error('OrderController@index called with wrong method', [
                'method' => $request->method(),
                'expected' => 'GET'
            ]);
            abort(405, 'Method not allowed');
        }
        
        $query = Order::with('items');
        
        if (Auth::check()) {
            $query->forUser(Auth::id());
            // Load relationship reader để sidebar hiển thị "Sách đang mượn" ngay
            Auth::user()->load('reader');
        } else {
            $query->forSession(Session::getId());
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Chỉ trả JSON nếu request từ API route (api/*)
        // Tất cả request từ web route (/orders) sẽ trả về HTML
        // KHÔNG kiểm tra expectsJson() để tránh browser cache header
        if ($request->is('api/*')) {
            \Log::info('Returning JSON for API request');
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        }
        
        // Force trả về HTML view cho browser với header rõ ràng
        // Đảm bảo không bao giờ trả về JSON cho web route
        // Bỏ qua tất cả Accept headers và luôn trả về HTML
        \Log::info('Returning HTML view for web request', [
            'orders_count' => $orders->count(),
            'view_path' => 'orders.index'
        ]);
        
        // Force HTML response - không bao giờ trả JSON cho web route
        $response = response()->view('orders.index', compact('orders'));
        
        // Set headers để force HTML
        $response->header('Content-Type', 'text/html; charset=utf-8');
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');
        
        return $response;
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

    /**
     * Lấy hoặc tạo PurchasableBook từ Book
     */
    private function getOrCreatePurchasableBook($bookId, $type = 'paper')
    {
        // Kiểm tra xem book_id có phải là PurchasableBook không
        $purchasableBook = PurchasableBook::find($bookId);
        if ($purchasableBook) {
            return $purchasableBook;
        }
        
        // Nếu không phải, tìm Book và tạo PurchasableBook tương ứng
        $book = Book::findOrFail($bookId);
        
        // Tìm PurchasableBook đã tồn tại với cùng identifier (dựa trên tên sách)
        $purchasableBook = PurchasableBook::where('ten_sach', $book->ten_sach)
            ->first();
        
        if ($purchasableBook) {
            // Đồng bộ số lượng tồn kho từ inventories
            $availableStockForPurchase = Inventory::where('book_id', $book->id)
                ->where('storage_type', 'Kho')
                ->where('status', 'Co san')
                ->count();
            
            // Nếu không có trong inventories, sử dụng so_luong từ bảng books
            $stockQuantity = $availableStockForPurchase > 0 ? $availableStockForPurchase : ($book->so_luong ?? 0);
            
            // Cập nhật số lượng tồn kho
            $purchasableBook->update(['so_luong_ton' => $stockQuantity]);
            
            return $purchasableBook;
        }
        
        // Tạo mới PurchasableBook
        $price = $book->gia ?? 111000;
        
        // Load publisher nếu có
        $book->load('publisher');
        
        // Tính số lượng tồn kho từ inventories
        $availableStockForPurchase = Inventory::where('book_id', $book->id)
            ->where('storage_type', 'Kho')
            ->where('status', 'Co san')
            ->count();
        
        // Nếu không có trong inventories, sử dụng so_luong từ bảng books
        $stockQuantity = $availableStockForPurchase > 0 ? $availableStockForPurchase : ($book->so_luong ?? 0);
        
        $purchasableBook = PurchasableBook::create([
            'ten_sach' => $book->ten_sach,
            'tac_gia' => $book->tac_gia ?? 'Chưa cập nhật',
            'mo_ta' => $book->mo_ta,
            'hinh_anh' => $book->hinh_anh,
            'gia' => $price,
            'nha_xuat_ban' => $book->publisher ? $book->publisher->ten_nha_xuat_ban : null,
            'nam_xuat_ban' => $book->nam_xuat_ban,
            'isbn' => $book->isbn ?? null,
            'so_trang' => $book->so_trang ?? null,
            'ngon_ngu' => 'Tiếng Việt',
            'dinh_dang' => 'PAPER',
            'kich_thuoc_file' => null,
            'trang_thai' => 'active',
            'so_luong_ton' => $stockQuantity,
            'so_luong_ban' => 0,
            'danh_gia_trung_binh' => 0,
            'so_luot_xem' => 0,
        ]);
        
        return $purchasableBook;
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Kiểm tra quyền truy cập
        if (!$this->canAccessOrder($order)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền hủy đơn hàng này'
            ], 403);
        }
        
        // Kiểm tra đơn hàng có thể hủy không
        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng này không thể hủy'
            ], 400);
        }
        
        // Validate lý do hủy
        try {
            $validated = $request->validate([
                'cancellation_reason' => 'required|string|min:10|max:500',
            ], [
                'cancellation_reason.required' => 'Vui lòng nhập lý do hủy đơn hàng',
                'cancellation_reason.min' => 'Lý do hủy đơn hàng phải có ít nhất 10 ký tự',
                'cancellation_reason.max' => 'Lý do hủy đơn hàng không được vượt quá 500 ký tự',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Cập nhật trạng thái đơn hàng và lý do hủy
            $order->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
            ]);
            
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
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được hủy thành công'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Order cancellation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}
