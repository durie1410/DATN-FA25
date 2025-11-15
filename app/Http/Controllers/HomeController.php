<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Borrow;
use App\Models\Reader;
use App\Models\Reservation;
use App\Models\PurchasableBook;
use App\Models\Document;
use App\Models\Author;
use App\Models\Inventory;
use App\Services\AuditService;
use Illuminate\Http\Request;

class HomeController extends Controller
{

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

    public function publisher()
    {
        // Categories with counts for sidebar and topics
        $categories = Category::withCount('books')->orderBy('ten_the_loai')->get();

        // Recent books
        $books = Book::with(['category'])
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        $newBooks = $books->take(10);
        
        // Sách nổi bật - ưu tiên sách có is_featured = true, sau đó lấy sách bán chạy hoặc mới nhất
        $featured = Book::with(['category'])
            ->orderByRaw('CASE WHEN is_featured = true THEN 0 ELSE 1 END')
            ->orderBy('so_luong_ban', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();
        
        $recommended = Book::with(['category'])
            ->inRandomOrder()
            ->limit(12)
            ->get();
        $categoriesTop = $categories->sortByDesc('books_count')->take(12);

        // Lấy tin tức (documents) - 1 tin nổi bật và 3 tin mới nhất
        $news = Document::orderBy('published_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
        
        $featuredNews = $news->count() > 0 ? $news->first() : null;
        $otherNews = $news->count() > 1 ? $news->skip(1)->take(3) : collect();

        return view('publisher-home', compact(
            'categories',
            'books',
            'newBooks',
            'featured',
            'recommended',
            'categoriesTop',
            'news',
            'featuredNews',
            'otherNews'
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

    public function trangchu()
    {
        // Lấy danh sách categories cho sidebar và phần Chủ đề
        $categories = Category::withCount('books')->orderBy('ten_the_loai')->get();
        $categoriesTop = $categories->sortByDesc('books_count')->take(12);

        // Lấy tất cả book_id từ kho (cả kho và trưng bày) để đảm bảo hiển thị tất cả sản phẩm
        $bookIdsFromInventory = Inventory::select('book_id')
            ->distinct()
            ->pluck('book_id')
            ->toArray();

        // 1. Lấy Sách Nổi bật (is_featured = true) - ưu tiên sách có trong kho
        // Bước 1: Lấy sách nổi bật có trong kho
        $featured_books = collect();
        if (!empty($bookIdsFromInventory)) {
            $featured_books = Book::where(function($query) {
                    $query->where('is_featured', true)
                          ->orWhere('is_featured', 1);
                })
                ->where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->whereIn('id', $bookIdsFromInventory)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
        
        // Bước 2: Nếu không đủ 10 sách, lấy thêm sách nổi bật không có trong kho
        if ($featured_books->count() < 10) {
            $excludedIds = $featured_books->pluck('id')->toArray();
            $additional_featured = Book::where(function($query) {
                    $query->where('is_featured', true)
                          ->orWhere('is_featured', 1);
                })
                ->where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->when(!empty($excludedIds), function($query) use ($excludedIds) {
                    $query->whereNotIn('id', $excludedIds);
                })
                ->orderBy('created_at', 'desc')
                ->limit(10 - $featured_books->count())
                ->get();
            $featured_books = $featured_books->merge($additional_featured);
        }
        
        // Bước 3: Nếu vẫn không có sách nổi bật, lấy sách mới nhất (bỏ qua điều kiện is_featured)
        if ($featured_books->isEmpty()) {
            $featured_books = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->when(!empty($bookIdsFromInventory), function($query) use ($bookIdsFromInventory) {
                    // Ưu tiên sách có trong kho
                    $query->whereIn('id', $bookIdsFromInventory);
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
        
        // Bước 4: Nếu vẫn không có sách có trong kho, lấy sách mới nhất không có trong kho
        if ($featured_books->isEmpty() && !empty($bookIdsFromInventory)) {
            $featured_books = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->whereNotIn('id', $bookIdsFromInventory)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
        
        // Bước 5: Nếu vẫn không có, lấy bất kỳ sách nào (không có điều kiện)
        if ($featured_books->isEmpty()) {
            $featured_books = Book::orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
        
        // Giới hạn tối đa 10 sách
        $featured_books = $featured_books->take(10);

        // 2. Lấy Sách Hay (Sách mới nhất, không phải nổi bật) - ưu tiên sách có trong kho
        $top_books = Book::where(function($query) {
                $query->where('trang_thai', 'active')
                      ->orWhereNull('trang_thai');
            })
            ->where(function($query) {
                $query->where('is_featured', false)
                      ->orWhereNull('is_featured')
                      ->orWhere('is_featured', 0);
            })
            ->when(!empty($bookIdsFromInventory), function($query) use ($bookIdsFromInventory) {
                // Ưu tiên sách có trong kho
                $query->whereIn('id', $bookIdsFromInventory);
            })
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        // Nếu không có sách hay, lấy sách mới nhất (bỏ qua điều kiện is_featured)
        if ($top_books->isEmpty()) {
            $top_books = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        }
        
        // Nếu vẫn không có, lấy bất kỳ sách nào
        if ($top_books->isEmpty()) {
            $top_books = Book::orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        }

        // 2.5. Lấy Tuyển tập hay nhất (dựa trên đánh giá và lượt xem)
        $best_collections = Book::where(function($query) {
                $query->where('trang_thai', 'active')
                      ->orWhereNull('trang_thai');
            })
            ->where(function($query) {
                $query->where('danh_gia_trung_binh', '>=', 4.0)
                      ->orWhere('so_luot_xem', '>', 100);
            })
            ->orderBy('danh_gia_trung_binh', 'desc')
            ->orderBy('so_luot_xem', 'desc')
            ->limit(6)
            ->get();
        
        // Nếu không có, lấy sách có lượt xem cao nhất
        if ($best_collections->isEmpty()) {
            $best_collections = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->orderBy('so_luot_xem', 'desc')
                ->limit(6)
                ->get();
        }
        
        // Nếu vẫn không có, lấy bất kỳ sách nào
        if ($best_collections->isEmpty()) {
            $best_collections = Book::orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        }

        // 3. Lấy Sách Mua/Xem nhiều nhất (top selling)
        $top_selling_books = Book::where('trang_thai', 'active')
            ->orderBy('so_luong_ban', 'desc')
            ->orderBy('so_luot_xem', 'desc')
            ->limit(5)
            ->get();

        // Nếu không có dữ liệu từ Book, lấy từ PurchasableBook
        if ($top_selling_books->isEmpty()) {
            $purchasableTop = PurchasableBook::active()
                ->orderBy('so_luong_ban', 'desc')
                ->limit(5)
                ->get();
            
            // Chuyển đổi PurchasableBook sang format tương tự Book
            $top_selling_books = $purchasableTop->map(function($item) {
                return (object)[
                    'id' => $item->id,
                    'ten_sach' => $item->ten_sach,
                    'so_luong_ban' => $item->so_luong_ban ?? 0,
                ];
            });
        }

        // 4. Lấy Văn bản Luật (cho phần "Điểm sách")
        $documents = Document::orderBy('published_date', 'desc')
            ->limit(5)
            ->get();

        // Nếu không có documents, lấy sách mới nhất làm tài liệu
        if ($documents->isEmpty()) {
            $document_books = Book::where('trang_thai', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $document_books = collect();
        }

        // 4.5. Lấy sách cho phần "Điểm sách" (1 sách lớn + 3 sách nhỏ)
        // Sách lớn: sách nổi bật hoặc sách mới nhất
        $diem_sach_featured = Book::where(function($query) {
                $query->where('trang_thai', 'active')
                      ->orWhereNull('trang_thai');
            })
            ->where(function($query) {
                $query->where('is_featured', true)
                      ->orWhere('is_featured', 1);
            })
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        // Nếu không có sách nổi bật, lấy sách mới nhất
        if (!$diem_sach_featured) {
            $diem_sach_featured = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->first();
        }

        // Nếu vẫn không có, lấy bất kỳ sách nào
        if (!$diem_sach_featured) {
            $diem_sach_featured = Book::orderBy('created_at', 'desc')->first();
        }

        // 3 sách nhỏ: sách mới nhất (không trùng với sách lớn)
        $diem_sach_list = collect();
        if ($diem_sach_featured) {
            $diem_sach_list = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->where('id', '!=', $diem_sach_featured->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        }

        // Nếu không đủ 3 sách, lấy thêm
        if ($diem_sach_list->count() < 3) {
            $additional_needed = 3 - $diem_sach_list->count();
            $excluded_ids = $diem_sach_list->pluck('id')->toArray();
            if ($diem_sach_featured) {
                $excluded_ids[] = $diem_sach_featured->id;
            }
            $additional = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->whereNotIn('id', $excluded_ids)
                ->orderBy('created_at', 'desc')
                ->limit($additional_needed)
                ->get();
            $diem_sach_list = $diem_sach_list->merge($additional);
        }

        // 6. Lấy Sách xem nhiều nhất (most viewed) - ưu tiên sách có trong kho
        $most_viewed_books = Book::where(function($query) {
                $query->where('trang_thai', 'active')
                      ->orWhereNull('trang_thai');
            })
            ->when(!empty($bookIdsFromInventory), function($query) use ($bookIdsFromInventory) {
                // Ưu tiên sách có trong kho
                $query->whereIn('id', $bookIdsFromInventory);
            })
            ->orderBy('so_luot_xem', 'desc')
            ->limit(9)
            ->get();

        // 5 & 7. Gộp Sách Mới và Có thể bạn thích - tổng cộng 6 sản phẩm
        // Lấy 3 sách mới nhất
        $new_books_temp = Book::where('trang_thai', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        $new_books_ids = $new_books_temp->pluck('id')->toArray();
        
        // Lấy 3 sách đề xuất (không trùng với sách mới)
        $recommended_books_temp = Book::where('trang_thai', 'active')
            ->whereNotIn('id', $new_books_ids)
            ->where(function($query) {
                $query->where('danh_gia_trung_binh', '>=', 4.0)
                      ->orWhere('so_luong_ban', '>', 0);
            })
            ->inRandomOrder()
            ->limit(3)
            ->get();
        
        // Nếu không đủ sách đề xuất, lấy thêm sách mới nhất
        if ($recommended_books_temp->count() < 3) {
            $additional_needed = 3 - $recommended_books_temp->count();
            $additional_books = Book::where('trang_thai', 'active')
                ->whereNotIn('id', array_merge($new_books_ids, $recommended_books_temp->pluck('id')->toArray()))
                ->orderBy('created_at', 'desc')
                ->limit($additional_needed)
                ->get();
            $recommended_books_temp = $recommended_books_temp->merge($additional_books);
        }
        
        // Gộp lại và lấy 6 sản phẩm
        $combined_books = $new_books_temp->merge($recommended_books_temp)->unique('id')->take(6);
        
        // Chia cho 2 section: 3 sách mới + 3 sách đề xuất
        $new_books = $combined_books->take(3);
        $recommended_books = $combined_books->skip(3)->take(3);

        // 8. Lấy Sách đề xuất bổ sung (cho phần đề xuất bên dưới) - 3 sản phẩm
        $suggested_books_ids = $combined_books->pluck('id')->toArray();
        $suggested_books = Book::where('trang_thai', 'active')
            ->whereNotIn('id', $suggested_books_ids)
            ->where(function($query) {
                $query->where('so_luot_xem', '>', 0)
                      ->orWhere('so_luong_ban', '>', 0);
            })
            ->orderBy('so_luot_xem', 'desc')
            ->orderBy('so_luong_ban', 'desc')
            ->limit(3)
            ->get();

        // Nếu không đủ sách, lấy thêm sách ngẫu nhiên
        if ($suggested_books->count() < 3) {
            $additional_needed = 3 - $suggested_books->count();
            $additional_suggested = Book::where('trang_thai', 'active')
                ->whereNotIn('id', array_merge($suggested_books_ids, $suggested_books->pluck('id')->toArray()))
                ->inRandomOrder()
                ->limit($additional_needed)
                ->get();
            $suggested_books = $suggested_books->merge($additional_suggested);
        }

        // 9. Lấy danh sách Tác giả (5 tác giả đang hoạt động)
        $authors = Author::active()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Nếu không có tác giả active, lấy tất cả tác giả
        if ($authors->isEmpty()) {
            $authors = Author::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // 10. Lấy tin tức (documents) - 1 tin nổi bật và 3 tin mới nhất
        $news = Document::orderBy('published_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
        
        $featuredNews = $news->count() > 0 ? $news->first() : null;
        $otherNews = $news->count() > 1 ? $news->skip(1)->take(3) : collect();

        return view('trangchu', compact(
            'categories',
            'categoriesTop',
            'featured_books',
            'top_books',
            'best_collections',
            'document_books',
            'documents',
            'top_selling_books',
            'new_books',
            'most_viewed_books',
            'recommended_books',
            'suggested_books',
            'diem_sach_featured',
            'diem_sach_list',
            'authors',
            'news',
            'featuredNews',
            'otherNews'
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
