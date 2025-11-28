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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function trangchu()
    {
        // Lấy danh sách categories cho sidebar và phần Chủ đề
        $categories = Category::withCount('books')->orderBy('ten_the_loai')->get();
        $categoriesTop = $categories->sortByDesc('books_count')->take(12);

        // Lấy tất cả book_id từ kho (cả kho và trưng bày) - CHỈ hiển thị sách đã có trong kho
        $bookIdsFromInventory = Inventory::select('book_id')
            ->distinct()
            ->pluck('book_id')
            ->toArray();

        // Nếu không có sách nào trong kho, trả về collections rỗng
        if (empty($bookIdsFromInventory)) {
            $featured_books = collect();
            $top_books = collect();
            $best_collections = collect();
            $top_selling_books = collect();
            $document_books = collect();
            $documents = Document::orderBy('published_date', 'desc')->limit(5)->get();
            $diem_sach_featured = null;
            $diem_sach_list = collect();
            $most_viewed_books = collect();
            $new_books = collect();
            $recommended_books = collect();
            $suggested_books = collect();
        } else {
            // 1. Lấy Sách Nổi bật (is_featured = true) - CHỈ lấy sách có trong kho
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
            
            // Nếu không có sách nổi bật, lấy sách mới nhất có trong kho
            if ($featured_books->isEmpty()) {
                $featured_books = Book::where(function($query) {
                        $query->where('trang_thai', 'active')
                              ->orWhereNull('trang_thai');
                    })
                    ->whereIn('id', $bookIdsFromInventory)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            }

            // 2. Lấy Sách Hay (Sách mới nhất, không phải nổi bật) - CHỈ lấy sách có trong kho
            $top_books = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->where(function($query) {
                    $query->where('is_featured', false)
                          ->orWhereNull('is_featured')
                          ->orWhere('is_featured', 0);
                })
                ->whereIn('id', $bookIdsFromInventory)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            // 2.5. Lấy Tuyển tập hay nhất (dựa trên đánh giá và lượt xem) - CHỈ lấy sách có trong kho
            $best_collections = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->where(function($query) {
                    $query->where('danh_gia_trung_binh', '>=', 4.0)
                          ->orWhere('so_luot_xem', '>', 100);
                })
                ->whereIn('id', $bookIdsFromInventory)
                ->orderBy('danh_gia_trung_binh', 'desc')
                ->orderBy('so_luot_xem', 'desc')
                ->limit(6)
                ->get();

            // 3. Lấy Sách Mua/Xem nhiều nhất (top selling) - CHỈ lấy sách có trong kho
            $top_selling_books = Book::where('trang_thai', 'active')
                ->whereIn('id', $bookIdsFromInventory)
                ->orderBy('so_luong_ban', 'desc')
                ->orderBy('so_luot_xem', 'desc')
                ->limit(5)
                ->get();

            // 4. Lấy Văn bản Luật (cho phần "Điểm sách")
            $documents = Document::orderBy('published_date', 'desc')
                ->limit(5)
                ->get();

            // Nếu không có documents, lấy sách mới nhất có trong kho làm tài liệu
            if ($documents->isEmpty()) {
                $document_books = Book::where('trang_thai', 'active')
                    ->whereIn('id', $bookIdsFromInventory)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            } else {
                $document_books = collect();
            }

            // 4.5. Lấy sách cho phần "Điểm sách" (1 sách lớn + 3 sách nhỏ) - CHỈ lấy sách có trong kho
            // Sách lớn: sách nổi bật hoặc sách mới nhất
            $diem_sach_featured = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->where(function($query) {
                    $query->where('is_featured', true)
                          ->orWhere('is_featured', 1);
                })
                ->whereIn('id', $bookIdsFromInventory)
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->first();

            // Nếu không có sách nổi bật, lấy sách mới nhất có trong kho
            if (!$diem_sach_featured) {
                $diem_sach_featured = Book::where(function($query) {
                        $query->where('trang_thai', 'active')
                              ->orWhereNull('trang_thai');
                    })
                    ->whereIn('id', $bookIdsFromInventory)
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
                    ->first();
            }

            // 3 sách nhỏ: sách mới nhất có trong kho (không trùng với sách lớn)
            $diem_sach_list = collect();
            if ($diem_sach_featured) {
                $diem_sach_list = Book::where(function($query) {
                        $query->where('trang_thai', 'active')
                              ->orWhereNull('trang_thai');
                    })
                    ->where('id', '!=', $diem_sach_featured->id)
                    ->whereIn('id', $bookIdsFromInventory)
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            } else {
                // Nếu không có sách lớn, lấy 4 sách mới nhất
                $diem_sach_list = Book::where(function($query) {
                        $query->where('trang_thai', 'active')
                              ->orWhereNull('trang_thai');
                    })
                    ->whereIn('id', $bookIdsFromInventory)
                    ->orderBy('created_at', 'desc')
                    ->limit(4)
                    ->get();
            }

            // 6. Lấy Sách xem nhiều nhất (most viewed) - CHỈ lấy sách có trong kho
            $most_viewed_books = Book::where(function($query) {
                    $query->where('trang_thai', 'active')
                          ->orWhereNull('trang_thai');
                })
                ->whereIn('id', $bookIdsFromInventory)
                ->orderBy('so_luot_xem', 'desc')
                ->limit(9)
                ->get();

            // 5 & 7. Gộp Sách Mới và Có thể bạn thích - tổng cộng 6 sản phẩm - CHỈ lấy sách có trong kho
            // Lấy 3 sách mới nhất có trong kho
            $new_books_temp = Book::where('trang_thai', 'active')
                ->whereIn('id', $bookIdsFromInventory)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            
            $new_books_ids = $new_books_temp->pluck('id')->toArray();
            
            // Lấy 3 sách đề xuất có trong kho (không trùng với sách mới)
            $recommended_books_temp = Book::where('trang_thai', 'active')
                ->whereIn('id', $bookIdsFromInventory)
                ->whereNotIn('id', $new_books_ids)
                ->where(function($query) {
                    $query->where('danh_gia_trung_binh', '>=', 4.0)
                          ->orWhere('so_luong_ban', '>', 0);
                })
                ->inRandomOrder()
                ->limit(3)
                ->get();
            
            // Gộp lại và lấy 6 sản phẩm
            $combined_books = $new_books_temp->merge($recommended_books_temp)->unique('id')->take(6);
            
            // Chia cho 2 section: 3 sách mới + 3 sách đề xuất
            $new_books = $combined_books->take(3);
            $recommended_books = $combined_books->skip(3)->take(3);

            // 8. Lấy Sách đề xuất bổ sung (cho phần đề xuất bên dưới) - 3 sản phẩm - CHỈ lấy sách có trong kho
            $suggested_books_ids = $combined_books->pluck('id')->toArray();
            $suggested_books = Book::where('trang_thai', 'active')
                ->whereIn('id', $bookIdsFromInventory)
                ->whereNotIn('id', $suggested_books_ids)
                ->where(function($query) {
                    $query->where('so_luot_xem', '>', 0)
                          ->orWhere('so_luong_ban', '>', 0);
                })
                ->orderBy('so_luot_xem', 'desc')
                ->orderBy('so_luong_ban', 'desc')
                ->limit(3)
                ->get();
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
        $distance = floatval($request->input('distance', 0));
        
        // Tính tiền ship dựa trên khoảng cách
        $tienShip = 0;
        if ($distance > 5) {
            // Nếu > 5km, mỗi km trên 5km thêm 5.000₫
            $extraKm = $distance - 5;
            $tienShip = (int) ($extraKm * 5000);
        }

        try {
            // Kiểm tra xem có phải là "đặt trước" không (dựa vào note)
            $isReservation = stripos($note, 'Đặt trước') !== false || stripos($note, 'dat truoc') !== false;

            if ($isReservation) {
                // Tạo yêu cầu đặt trước (Reservation) - hiển thị trong "Đặt Trước"
                $reservation = Reservation::create([
                    'book_id' => $book->id,
                    'reader_id' => $reader->id,
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                    'priority' => 1,
                    'reservation_date' => now()->toDateString(),
                    'expiry_date' => now()->addDays(7)->toDateString(),
                    'notes' => trim($note . (empty($note) ? '' : ' ') . "(Đặt trước $borrowDays ngày)"),
                ]);

                // Ghi log phục vụ audit
                AuditService::logBorrow($reservation, "Reservation request for '{$book->ten_sach}' created by {$reader->ho_ten}");

                return response()->json([
                    'success' => true,
                    'message' => 'Đã gửi yêu cầu đặt trước. Vui lòng chờ quản trị viên duyệt.',
                    'data' => [
                        'reservation_id' => $reservation->id,
                        'expires_on' => $reservation->expiry_date->format('d/m/Y')
                    ]
                ]);
            } else {
                // Tạo yêu cầu mượn sách (Borrow + BorrowItem) - hiển thị trong "Quản lý mượn sách"
                
                DB::beginTransaction();
                try {
                    // Tìm một inventory có sẵn (nếu có)
                    $availableInventory = Inventory::where('book_id', $book->id)
                        ->where('status', 'Co san')
                        ->first();

                    // Parse địa chỉ từ reader nếu có (hoặc để null, admin sẽ cập nhật sau)
                    $diaChi = $reader->dia_chi ?? '';
                    $parts = explode(',', $diaChi);
                    
                    // Tạo phiếu mượn
                    $borrow = Borrow::create([
                        'reader_id' => $reader->id,
                        'ten_nguoi_muon' => $reader->ho_ten,
                        'so_dien_thoai' => $reader->so_dien_thoai ?? '',
                        'tinh_thanh' => count($parts) > 2 ? trim($parts[count($parts) - 1]) : '',
                        'huyen' => count($parts) > 1 ? trim($parts[count($parts) - 2]) : '',
                        'xa' => count($parts) > 0 ? trim($parts[0]) : '',
                        'so_nha' => '',
                        'ngay_muon' => now()->toDateString(),
                        'trang_thai' => 'Dang muon',
                        'tien_coc' => 0,
                        'tien_thue' => 0,
                        'tien_ship' => 0,
                        'tong_tien' => 0,
                        'ghi_chu' => trim($note . (empty($note) ? '' : ' ') . "(Yêu cầu mượn $borrowDays ngày)"),
                    ]);

                    // Tính ngày hẹn trả
                    $ngayHenTra = now()->addDays($borrowDays)->toDateString();

                    // Tạo BorrowItem với trạng thái 'Cho duyet'
                    $borrowItem = \App\Models\BorrowItem::create([
                        'borrow_id' => $borrow->id,
                        'book_id' => $book->id,
                        'inventorie_id' => $availableInventory ? $availableInventory->id : null,
                        'ngay_muon' => now()->toDateString(),
                        'ngay_hen_tra' => $ngayHenTra,
                        'trang_thai' => 'Cho duyet',
                        'trang_thai_coc' => 'cho_xu_ly',
                        'tien_coc' => 0,
                        'tien_thue' => 0,
                        'tien_ship' => $tienShip,
                        'ghi_chu' => trim($note . (empty($note) ? '' : ' ') . "(Yêu cầu mượn $borrowDays ngày)" . ($distance > 0 ? " - Khoảng cách: {$distance}km" : '')),
                    ]);

                    // Cập nhật tổng tiền của Borrow (tính từ items)
                    $borrow->recalculateTotals();

                    // Refresh relationship để đảm bảo items được load
                    $borrow->load('items');

                    // Nếu có inventory, cập nhật trạng thái tạm thời (sẽ được xác nhận khi admin duyệt)
                    // Không cập nhật ngay để admin có thể chọn lại inventory khi duyệt

                    DB::commit();

                    // Ghi log phục vụ audit
                    AuditService::logBorrow($borrow, "Borrow request for '{$book->ten_sach}' created by {$reader->ho_ten}");

                    Log::info('Borrow created successfully', [
                        'borrow_id' => $borrow->id,
                        'borrow_item_id' => $borrowItem->id,
                        'book_id' => $book->id,
                        'reader_id' => $reader->id,
                        'status' => $borrowItem->trang_thai
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Đã gửi yêu cầu mượn sách. Vui lòng chờ quản trị viên duyệt.',
                        'data' => [
                            'borrow_id' => $borrow->id,
                            'borrow_item_id' => $borrowItem->id,
                            'ngay_hen_tra' => $ngayHenTra
                        ]
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error creating borrow and borrow item', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'book_id' => $book->id,
                        'reader_id' => $reader->id
                    ]);
                    throw $e; // Re-throw để catch block bên ngoài xử lý
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Unique constraint violation hoặc lỗi database
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            
            Log::error('Database error creating borrow/reservation:', [
                'code' => $errorCode,
                'message' => $errorMessage,
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? []
            ]);
            
            // Kiểm tra xem có phải lỗi enum không (chỉ khi thực sự là lỗi enum)
            // Lưu ý: Chỉ bắt lỗi enum khi thực sự có lỗi enum, không phải mọi lỗi có chứa "trang_thai"
            $isEnumError = (
                strpos($errorMessage, 'Data truncated') !== false && strpos($errorMessage, 'trang_thai') !== false
            ) || (
                strpos($errorMessage, 'Invalid enum value') !== false
            ) || (
                strpos($errorMessage, 'trang_thai') !== false && 
                (strpos($errorMessage, 'enum') !== false || strpos($errorMessage, 'ENUM') !== false)
            );
            
            if ($isEnumError) {
                // Kiểm tra lại enum trong database
                try {
                    $enumResult = DB::select("SHOW COLUMNS FROM borrow_items WHERE Field = 'trang_thai'");
                    $enumType = !empty($enumResult) ? $enumResult[0]->Type : 'N/A';
                    
                    Log::error('Enum error detected', [
                        'database_enum' => $enumType,
                        'trying_to_insert' => 'Cho duyet',
                        'full_error' => $errorMessage,
                        'sql' => $e->getSql() ?? 'N/A',
                        'bindings' => $e->getBindings() ?? []
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Lỗi trạng thái: Enum trong database không khớp. Enum hiện tại: ' . $enumType . '. Vui lòng liên hệ quản trị viên.',
                        'error' => $errorMessage,
                        'debug' => config('app.debug') ? [
                            'enum_in_db' => $enumType,
                            'trying_to_insert' => 'Cho duyet'
                        ] : null
                    ], 400);
                } catch (\Exception $checkException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lỗi trạng thái: ' . $errorMessage,
                        'error' => $errorMessage
                    ], 400);
                }
            }
            
            // Kiểm tra duplicate
            if ($errorCode == 23000 || strpos($errorMessage, 'Duplicate') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã gửi yêu cầu cho sách này trước đó. Vui lòng chờ duyệt.'
                ], 400);
            }
            
            // Trả về lỗi chi tiết hơn cho các lỗi database khác
            Log::error('Database error (non-enum)', [
                'code' => $errorCode,
                'message' => $errorMessage,
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? []
            ]);
            
            $userMessage = config('app.debug') 
                ? 'Lỗi database: ' . $errorMessage
                : 'Có lỗi xảy ra khi lưu dữ liệu. Vui lòng thử lại hoặc liên hệ quản trị viên.';
            
            return response()->json([
                'success' => false,
                'message' => $userMessage,
                'error' => config('app.debug') ? $errorMessage : 'Database error',
                'debug' => config('app.debug') ? [
                    'code' => $errorCode,
                    'sql' => $e->getSql() ?? null,
                    'bindings' => $e->getBindings() ?? []
                ] : null
            ], 400);
        } catch (\Exception $e) {
            Log::error('Create borrow/reservation error:', [
                'message' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'class' => get_class($e)
            ]);
            
            $userMessage = 'Có lỗi xảy ra khi gửi yêu cầu. ';
            if (config('app.debug')) {
                $userMessage .= $e->getMessage();
            } else {
                $userMessage .= 'Vui lòng thử lại hoặc liên hệ quản trị viên.';
            }
            
            return response()->json([
                'success' => false,
                'message' => $userMessage,
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

}
