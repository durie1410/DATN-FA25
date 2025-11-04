<?php

namespace App\Http\Controllers;

use App\Models\PurchasableBook;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasableBookController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchasableBook::active();

        // Tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_sach', 'like', "%{$keyword}%")
                  ->orWhere('tac_gia', 'like', "%{$keyword}%")
                  ->orWhere('nha_xuat_ban', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('gia', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('gia', '<=', $request->max_price);
        }

        // Lọc theo định dạng
        if ($request->filled('format')) {
            $query->where('dinh_dang', $request->format);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'so_luong_ban');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderByPrice($sortOrder);
                break;
            case 'rating':
                $query->orderByRating($sortOrder);
                break;
            case 'sales':
                $query->orderBySales($sortOrder);
                break;
            case 'views':
                $query->orderBy('so_luot_xem', $sortOrder);
                break;
            case 'name':
                $query->orderBy('ten_sach', $sortOrder);
                break;
            default:
                $query->orderBySales('asc');
        }

        $books = $query->paginate(12);

        // Kiểm tra user đã mua sách nào chưa
        $purchasedBookIds = [];
        if (auth()->check()) {
            $purchasedBookIds = OrderItem::whereHas('order', function($query) {
                $query->where('user_id', auth()->id())
                      ->whereIn('status', ['processing', 'shipped', 'delivered'])
                      ->whereIn('payment_status', ['paid']);
            })->pluck('purchasable_book_id')->toArray();
        }

        return view('purchasable-books.index', compact('books', 'purchasedBookIds'));
    }

    public function show($id)
    {
        $book = PurchasableBook::active()->findOrFail($id);
        
        // Tăng lượt xem
        $book->incrementViews();
        
        // Lấy sách liên quan (cùng tác giả hoặc định dạng)
        $relatedBooks = PurchasableBook::active()
            ->where('id', '!=', $id)
            ->where(function($q) use ($book) {
                $q->where('tac_gia', $book->tac_gia)
                  ->orWhere('dinh_dang', $book->dinh_dang);
            })
            ->limit(4)
            ->get();

        // Kiểm tra user đã mua sách này chưa
        $isPurchased = false;
        if (auth()->check()) {
            $isPurchased = $book->isPurchasedBy(auth()->id());
        }

        return view('purchasable-books.show', compact('book', 'relatedBooks', 'isPurchased'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:purchasable_books,id',
            'payment_method' => 'required|in:credit_card,bank_transfer'
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để mua sách'
            ], 401);
        }

        $book = PurchasableBook::active()->findOrFail($request->book_id);

        // Kiểm tra sách còn hàng không
        if (!$book->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Sách này đã hết hàng'
            ], 400);
        }

        // Kiểm tra user đã mua sách này chưa
        if ($book->isPurchasedBy(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã mua sách này rồi'
            ], 400);
        }

        DB::beginTransaction();
        
        try {
            // Tạo đơn hàng
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'customer_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => '0123456789', // Mặc định
                'customer_address' => 'Chưa cập nhật', // Mặc định
                'subtotal' => $book->gia,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'total_amount' => $book->gia,
                'status' => 'delivered', // Đặt thành delivered để coi như đã giao hàng
                'payment_status' => 'paid', // Đặt thành paid để coi như đã thanh toán
                'payment_method' => $request->payment_method,
                'notes' => 'Mua sách điện tử trực tiếp'
            ]);

            // Tạo order item
            OrderItem::create([
                'order_id' => $order->id,
                'purchasable_book_id' => $book->id,
                'book_title' => $book->ten_sach,
                'book_author' => $book->tac_gia,
                'price' => $book->gia,
                'quantity' => 1,
                'total_price' => $book->gia,
            ]);

            // Giảm số lượng tồn kho và tăng số lượng bán
            $book->decreaseStock(1);
            $book->incrementSales();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã mua sách! Chúng tôi sẽ gửi file sách qua email trong vòng 24 giờ.',
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.'
            ], 500);
        }
    }
}
