<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PurchasableBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $request->validate([
            'book_id' => 'required|exists:purchasable_books,id',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $quantity = $request->quantity ?? 1;
        $book = PurchasableBook::findOrFail($request->book_id);
        
        // Kiểm tra số lượng tồn kho
        if (!$book->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Sách này đã hết hàng'
            ], 400);
        }
        
        // Kiểm tra số lượng có đủ không
        if ($book->so_luong_ton < $quantity) {
            return response()->json([
                'success' => false,
                'message' => "Chỉ còn {$book->so_luong_ton} bản trong kho"
            ], 400);
        }
        
        $cart = $this->getCurrentCart();
        
        try {
            CartItem::addOrUpdate($cart->id, $request->book_id, $quantity);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sách vào giỏ hàng',
                'cart_count' => $cart->fresh()->total_items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm sách vào giỏ hàng'
            ], 500);
        }
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
    public function transferToUser($userId)
    {
        $sessionId = Session::getId();
        $sessionCart = Cart::forSession($sessionId)->active()->first();
        
        if ($sessionCart && !$sessionCart->isEmpty()) {
            $userCart = Cart::getOrCreateForUser($userId);
            
            // Chuyển các item từ session cart sang user cart
            foreach ($sessionCart->items as $item) {
                CartItem::addOrUpdate($userCart->id, $item->purchasable_book_id, $item->quantity);
            }
            
            // Xóa session cart
            $sessionCart->items()->delete();
            $sessionCart->delete();
        }
    }
}