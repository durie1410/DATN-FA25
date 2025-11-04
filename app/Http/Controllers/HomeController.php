<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Borrow;
use App\Models\Reader;
use App\Models\Reservation;
use App\Models\PurchasableBook;
use App\Models\OrderItem;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy thống kê
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $newBooks = Book::whereYear('created_at', now()->year)->count();
        $popularBooks = Book::count(); // Giả sử tất cả sách đều phổ biến
        
        // Lấy danh sách thể loại
        $categories = Category::all();
        
        // Lấy sách mới nhất (cho khu mượn sách)
        $books = Book::with(['category', 'borrows', 'inventories'])
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();
        
        // Lấy các nhóm sách theo category
        $rankingBooks = Book::with(['category', 'inventories'])
            ->inRandomOrder()
            ->limit(10)
            ->get();
        
        $wakaRecommendedBooks = Book::with(['category', 'inventories'])
            ->where('nam_xuat_ban', '>=', 2020)
            ->inRandomOrder()
            ->limit(8)
            ->get();
        
        $minimalistBooks = Category::where('ten_the_loai', 'LIKE', '%Phát triển%')
            ->first()
            ?->books()
            ->with(['inventories'])
            ->inRandomOrder()
            ->limit(8)
            ->get() ?? collect();
        
        $spiritualBooks = Category::where('ten_the_loai', 'LIKE', '%Văn học%')
            ->first()
            ?->books()
            ->with(['inventories'])
            ->inRandomOrder()
            ->limit(8)
            ->get() ?? collect();
        
        $healingBooks = Category::where('ten_the_loai', 'LIKE', '%Tâm lý%')
            ->first()
            ?->books()
            ->with(['inventories'])
            ->inRandomOrder()
            ->limit(8)
            ->get() ?? Book::with(['inventories'])->inRandomOrder()->limit(8)->get();
        
        $motivationalBooks = Category::where('ten_the_loai', 'LIKE', '%Kinh doanh%')
            ->first()
            ?->books()
            ->with(['inventories'])
            ->inRandomOrder()
            ->limit(8)
            ->get() ?? Book::with(['inventories'])->inRandomOrder()->limit(8)->get();
        
        // Lấy sách có thể mua (cho khu mua sách) - sắp xếp theo bán chạy và đánh giá cao
        $purchasableBooks = PurchasableBook::active()
            ->orderBy('so_luong_ban', 'desc')
            ->orderBy('danh_gia_trung_binh', 'desc')
            ->limit(6)
            ->get();
        
        // Lấy thông tin tác giả nếu đã đăng nhập
        $currentReader = null;
        if (auth()->check()) {
            $currentReader = Reader::where('user_id', auth()->id())->first();
        }
        
        // Kiểm tra user đã mua sách nào chưa
        $purchasedBookIds = [];
        if (auth()->check()) {
            $purchasedBookIds = OrderItem::whereHas('order', function($query) {
                $query->where('user_id', auth()->id())
                      ->whereIn('status', ['processing', 'shipped', 'delivered'])
                      ->whereIn('payment_status', ['paid']);
            })->pluck('purchasable_book_id')->toArray();
        }
        
        return view('home', compact(
            'totalBooks',
            'totalCategories', 
            'newBooks',
            'popularBooks',
            'categories',
            'books',
            'rankingBooks',
            'wakaRecommendedBooks',
            'minimalistBooks',
            'spiritualBooks',
            'healingBooks',
            'motivationalBooks',
            'purchasableBooks',
            'currentReader',
            'purchasedBookIds'
        ));
    }

    public function modern()
    {
        // Lấy thống kê cho trang chủ hiện đại
        $stats = [
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'total_readers' => Reader::count(),
            'new_books' => Book::whereYear('created_at', now()->year)->count(),
        ];
        
        // Lấy danh sách thể loại với số lượng sách
        $categories = Category::withCount('books')->get();
        
        // Lấy sách nổi bật (mới nhất)
        $featuredBooks = Book::with(['category', 'inventories'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        // Lấy thông tin độc giả hiện tại
        $currentReader = null;
        if (auth()->check()) {
            $currentReader = Reader::where('user_id', auth()->id())->first();
        }
        
        // Lấy sách cho showcase với thông tin mượn
        $books = Book::with(['category', 'borrows', 'inventories'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        // Thêm thông tin trạng thái mượn cho mỗi sách
        foreach ($books as $book) {
            $book->available_copies = $book->inventories->where('status', 'Co san')->count();
            if ($currentReader) {
                $book->user_borrowed = \App\Models\Borrow::where('book_id', $book->id)
                    ->where('reader_id', $currentReader->id)
                    ->where('trang_thai', 'Dang muon')
                    ->exists();
            } else {
                $book->user_borrowed = false;
            }
        }
        
        return view('modern-homepage', compact(
            'stats',
            'categories',
            'featuredBooks',
            'books',
            'currentReader'
        ));
    }

    public function testSimple()
    {
        // Lấy thống kê cho trang test đơn giản
        $stats = [
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'total_readers' => Reader::count(),
            'new_books' => Book::whereYear('created_at', now()->year)->count(),
        ];
        
        // Lấy danh sách thể loại với số lượng sách
        $categories = Category::withCount('books')->get();
        
        // Lấy sách nổi bật (mới nhất)
        $featuredBooks = Book::with(['category', 'inventories'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        return view('test-simple', compact(
            'stats',
            'categories',
            'featuredBooks'
        ));
    }

    public function borrowBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrow_days' => 'nullable|integer|min:1|max:30',
            'note' => 'nullable|string|max:1000',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để gửi yêu cầu mượn sách'
            ], 401);
        }

        $reader = Reader::where('user_id', auth()->id())->first();
        if (!$reader) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa có thẻ độc giả. Vui lòng đăng ký thẻ độc giả trước khi mượn sách.',
                'redirect' => route('register.reader.form')
            ], 400);
        }

        if ($reader->trang_thai !== 'Hoat dong') {
            return response()->json([
                'success' => false,
                'message' => 'Thẻ độc giả của bạn đã bị khóa hoặc tạm dừng. Vui lòng liên hệ thư viện.'
            ], 400);
        }

        if ($reader->ngay_het_han < now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Thẻ độc giả của bạn đã hết hạn. Vui lòng gia hạn thẻ.'
            ], 400);
        }

        $book = Book::findOrFail($request->book_id);

        $borrowDays = (int) $request->input('borrow_days', 14);
        $note = $request->input('note', '');

        try {
            // Tạo yêu cầu mượn dưới dạng đặt trước (pending) chờ quản trị duyệt
            $reservation = Reservation::create([
                'book_id' => $book->id,
                'reader_id' => $reader->id,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'priority' => 1,
                'reservation_date' => now()->toDateString(),
                'expiry_date' => now()->addDays(7)->toDateString(),
                'notes' => trim($note . (empty($note) ? '' : ' ') . "(Yêu cầu mượn $borrowDays ngày)"),
            ]);

            // Ghi log phục vụ audit
            AuditService::logBorrow($reservation, "Borrow request for '{$book->ten_sach}' created by {$reader->ho_ten}");

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi yêu cầu mượn. Vui lòng chờ quản trị viên duyệt.',
                'data' => [
                    'reservation_id' => $reservation->id,
                    'expires_on' => $reservation->expiry_date->format('d/m/Y')
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Unique(book_id, user_id): người dùng đã có yêu cầu cho sách này
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã gửi yêu cầu mượn cho sách này trước đó. Vui lòng chờ duyệt.'
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Create reservation error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi yêu cầu mượn. Vui lòng thử lại.'
            ], 500);
        }
    }
}
