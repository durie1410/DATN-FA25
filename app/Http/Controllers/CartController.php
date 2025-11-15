<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PurchasableBook;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cart = $this->getCurrentCart();
        $cartItems = $cart->items()->with('purchasableBook')->get();
        
        return view('cart.index', compact('cart', 'cartItems'));
    }

    /**
     * Thêm sách vào giỏ hàng
     */
    public function add(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng!',
                'redirect_url' => route('login')
            ], 401);
        }

        $request->validate([
            'book_id' => 'required',
            'paper_quantity' => 'required|integer|min:1|max:10'
        ]);

        $cart = $this->getCurrentCart();
        $paperQuantity = $request->paper_quantity ?? 0;
        
        // Kiểm tra số lượng sách giấy
        if ($paperQuantity == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn số lượng sách!'
            ], 400);
        }

        try {
            // Xử lý sách giấy
            $purchasableBook = $this->getOrCreatePurchasableBook($request->book_id, 'paper');
            
            if (!$purchasableBook->isInStock()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sách giấy này đã hết hàng'
                ], 400);
            }
            
            if ($purchasableBook->so_luong_ton < $paperQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Sách giấy chỉ còn {$purchasableBook->so_luong_ton} bản trong kho"
                ], 400);
            }
            
            CartItem::addOrUpdate($cart->id, $purchasableBook->id, $paperQuantity);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sách vào giỏ hàng',
                'cart_count' => $cart->fresh()->total_items
            ]);
        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm sách vào giỏ hàng: ' . $e->getMessage()
            ], 500);
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
     * Cập nhật số lượng sách trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cartItem = CartItem::findOrFail($id);
        
        // Kiểm tra quyền sở hữu
        if (!$this->canAccessCart($cartItem->cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập'
            ], 403);
        }

        // Kiểm tra số lượng tồn kho
        $book = $cartItem->purchasableBook;
        if (!$book->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Sách này đã hết hàng'
            ], 400);
        }
        
        if ($book->so_luong_ton < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => "Chỉ còn {$book->so_luong_ton} bản trong kho"
            ], 400);
        }

        try {
            $cartItem->updateQuantity($request->quantity);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng',
                'cart_count' => $cartItem->cart->fresh()->total_items,
                'total_price' => number_format($cartItem->fresh()->total_price, 0, ',', '.') . ' VNĐ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật'
            ], 500);
        }
    }

    /**
     * Xóa sách khỏi giỏ hàng
     */
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // Kiểm tra quyền sở hữu
        if (!$this->canAccessCart($cartItem->cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập'
            ], 403);
        }

        try {
            $cart = $cartItem->cart;
            $cartItem->delete();
            $cart->recalculateTotals();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sách khỏi giỏ hàng',
                'cart_count' => $cart->fresh()->total_items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa'
            ], 500);
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        $cart = $this->getCurrentCart();
        
        try {
            $cart->items()->delete();
            $cart->update(['total_amount' => 0, 'total_items' => 0]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa toàn bộ giỏ hàng'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa giỏ hàng'
            ], 500);
        }
    }

    /**
     * Lấy số lượng sách trong giỏ hàng (AJAX)
     */
    public function count()
    {
        $cart = $this->getCurrentCart();
        
        return response()->json([
            'count' => $cart->total_items
        ]);
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
     * Kiểm tra quyền truy cập giỏ hàng
     */
    private function canAccessCart($cart)
    {
        if (Auth::check()) {
            return $cart->user_id === Auth::id();
        } else {
            return $cart->session_id === Session::getId();
        }
    }

    /**
     * Chuyển giỏ hàng từ session sang user khi đăng nhập
     */
    public function transferToUser($userId, $oldSessionId = null)
    {
        try {
            // Sử dụng session ID cũ nếu được cung cấp (trước khi regenerate)
            // Nếu không, sử dụng session ID hiện tại
            $sessionId = $oldSessionId ?? Session::getId();
            $currentSessionId = Session::getId();
            
            Log::info('TransferToUser: Starting transfer', [
                'user_id' => $userId,
                'old_session_id' => $oldSessionId,
                'session_id' => $sessionId,
                'current_session_id' => $currentSessionId
            ]);
            
            // Tìm tất cả giỏ hàng session có thể có
            // Ưu tiên tìm theo session ID cũ (nếu có)
            $sessionCarts = collect();
            
            if ($oldSessionId) {
                // Tìm theo session ID cũ (trước khi regenerate)
                $cartsByOldSession = Cart::where('session_id', $oldSessionId)
                    ->where('status', 'active')
                    ->whereNull('user_id')
                    ->where('created_at', '>=', now()->subDay())
                    ->get();
                
                $sessionCarts = $sessionCarts->merge($cartsByOldSession);
                
                Log::info('TransferToUser: Found carts by old session ID', [
                    'old_session_id' => $oldSessionId,
                    'count' => $cartsByOldSession->count()
                ]);
            }
            
            // Nếu session ID hiện tại khác với session ID cũ, cũng tìm theo session ID hiện tại
            if ($oldSessionId !== $currentSessionId) {
                $cartsByCurrentSession = Cart::where('session_id', $currentSessionId)
                    ->where('status', 'active')
                    ->whereNull('user_id')
                    ->where('created_at', '>=', now()->subDay())
                    ->get();
                
                $sessionCarts = $sessionCarts->merge($cartsByCurrentSession);
                
                Log::info('TransferToUser: Found carts by current session ID', [
                    'current_session_id' => $currentSessionId,
                    'count' => $cartsByCurrentSession->count()
                ]);
            }
            
            // Loại bỏ trùng lặp
            $sessionCarts = $sessionCarts->unique('id');
            
            Log::info('TransferToUser: Found session carts', [
                'count' => $sessionCarts->count(),
                'session_ids' => $sessionCarts->pluck('session_id')->toArray()
            ]);
            
            if ($sessionCarts->isEmpty()) {
                Log::info('TransferToUser: No session carts found');
                return;
            }
            
            $userCart = Cart::getOrCreateForUser($userId);
            
            $transferredItems = 0;
            
            // Chuyển các item từ tất cả session carts sang user cart
            foreach ($sessionCarts as $sessionCart) {
                $items = $sessionCart->items()->get();
                
                Log::info('TransferToUser: Processing session cart', [
                    'cart_id' => $sessionCart->id,
                    'session_id' => $sessionCart->session_id,
                    'items_count' => $items->count()
                ]);
                
                foreach ($items as $item) {
                    try {
                        CartItem::addOrUpdate($userCart->id, $item->purchasable_book_id, $item->quantity);
                        $transferredItems++;
                        
                        Log::info('TransferToUser: Item transferred', [
                            'item_id' => $item->id,
                            'purchasable_book_id' => $item->purchasable_book_id,
                            'quantity' => $item->quantity
                        ]);
                    } catch (\Exception $e) {
                        Log::error('TransferToUser: Error transferring item', [
                            'item_id' => $item->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                // Xóa session cart sau khi chuyển xong
                $sessionCart->items()->delete();
                $sessionCart->delete();
            }
            
            // Cập nhật lại tổng của user cart
            $userCart->recalculateTotals();
            
            Log::info('TransferToUser: Transfer completed', [
                'user_id' => $userId,
                'transferred_items' => $transferredItems,
                'user_cart_total_items' => $userCart->fresh()->total_items
            ]);
            
        } catch (\Exception $e) {
            Log::error('TransferToUser: Error during transfer', [
                'user_id' => $userId,
                'old_session_id' => $oldSessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}