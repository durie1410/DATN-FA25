<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\InventoryReceipt;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Services\CacheService;
use App\Services\FileUploadService;
use App\Http\Requests\BookRequest;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Lọc theo thể loại (nếu có)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Tìm kiếm theo tên sách hoặc tác giả
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_sach', 'like', "%{$keyword}%")
                  ->orWhere('tac_gia', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo sách có/không có trong kho
        if ($request->filled('inventory_status')) {
            $bookIdsInInventory = Inventory::select('book_id')->distinct()->pluck('book_id')->toArray();
            
            if ($request->inventory_status === 'has_inventory') {
                // Chỉ hiển thị sách có trong kho
                $query->whereIn('id', $bookIdsInInventory);
            } elseif ($request->inventory_status === 'no_inventory') {
                // Chỉ hiển thị sách không có trong kho
                if (!empty($bookIdsInInventory)) {
                    $query->whereNotIn('id', $bookIdsInInventory);
                }
            }
        }

        // Lấy danh sách sách sau khi lọc với phân trang
        $books = $query->orderBy('id', 'asc')->paginate(10);

        // Lấy danh sách thể loại để hiển thị dropdown (cached)
        $categories = CacheService::getCategories();
        
        // Tính toán thống kê
        $totalBooks = Book::count();
        $bookIdsInInventory = Inventory::select('book_id')->distinct()->pluck('book_id')->toArray();
        $booksWithInventory = count($bookIdsInInventory);
        $booksWithoutInventory = $totalBooks - $booksWithInventory;

        return view('admin.books.index', compact('books', 'categories', 'totalBooks', 'booksWithInventory', 'booksWithoutInventory'));
    }

    public function show($id)
    {
        $book = Book::with([
            'category',
            'reviews.user',
            'reviews.comments.user',
            'inventories',
            'favorites.user'
        ])->findOrFail($id);

        // Lấy sách liên quan (cùng thể loại)
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->with('category')
            ->limit(4)
            ->get();

        // Lấy lịch sử mượn (BorrowItem) của cuốn sách này
        $borrowItems = \App\Models\BorrowItem::where('book_id', $book->id)
            ->with([
                'borrow.reader',
                'borrow.librarian',
                'inventory'
            ])
            ->orderBy('ngay_muon', 'desc')
            ->get();

        // Thống kê sách
        $stats = [
            'total_reviews' => $book->reviews()->count(),
            'average_rating' => $book->reviews()->avg('rating') ?? 0,
            'total_favorites' => $book->favorites()->count(),
            'total_copies' => $book->inventories()->count(),
            'available_copies' => $book->inventories()->where('status', 'Co san')->count(),
            'borrowed_copies' => $book->inventories()->where('status', 'Dang muon')->count(),
            'total_borrows' => $borrowItems->count(),
        ];

        // Kiểm tra user hiện tại có yêu thích sách này không
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = $book->favorites()->where('user_id', auth()->id())->exists();
        }

        // Kiểm tra user hiện tại có đánh giá sách này không
        $userReview = null;
        if (auth()->check()) {
            $userReview = $book->reviews()->where('user_id', auth()->id())->first();
        }

        return view('admin.books.show', compact('book', 'relatedBooks', 'stats', 'isFavorited', 'userReview', 'borrowItems'));
    }

    public function create()
    {
        $categories = CacheService::getActiveCategories();
        return view('admin.books.create', compact('categories'));
    }

    public function store(BookRequest $request)
    {
        // Validation đã được xử lý trong BookRequest

        $path = null;
        if ($request->hasFile('hinh_anh')) {
            try {
                $result = FileUploadService::uploadImage(
                    $request->file('hinh_anh'),
                    'books',
                    [
                        'max_size' => 2048, // 2MB
                        'resize' => true,
                        'width' => 800,
                        'height' => 800,
                    ]
                );
                $path = $result['path'];
            } catch (\Exception $e) {
                \Log::error('Upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()
                    ->withErrors(['hinh_anh' => $e->getMessage()])
                    ->withInput();
            }
        }

        try {
            $book = Book::create([
                'ten_sach' => $request->ten_sach,
                'category_id' => $request->category_id,
                'nha_xuat_ban_id' => $request->nha_xuat_ban_id,
                'tac_gia' => $request->tac_gia,
                'nam_xuat_ban' => $request->nam_xuat_ban,
                'hinh_anh' => $path,
                'mo_ta' => $request->mo_ta,
                'gia' => $request->gia,
                'trang_thai' => $request->trang_thai,
                'danh_gia_trung_binh' => 0,
                'so_luong_ban' => 0,
                'so_luot_xem' => 0,
            ]);

            // Log book creation
            AuditService::logCreated($book, "Book '{$book->ten_sach}' created");
            
            // Clear dashboard cache
            CacheService::clearDashboard();

            return redirect()->route('admin.books.index')->with('success', 'Thêm sách thành công!');
        } catch (\Exception $e) {
            \Log::error('Create Book error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Lỗi khi tạo sách: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = CacheService::getActiveCategories();
        $publishers = \App\Models\Publisher::where('trang_thai', 'active')->orderBy('ten_nha_xuat_ban')->get();
        return view('admin.books.edit', compact('book', 'categories', 'publishers'));
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);

            // Store old values before update for audit log
            $oldValues = $book->getAttributes();

            $request->validate([
                'ten_sach' => 'required|max:255',
                'category_id' => 'required',
                'tac_gia' => 'required',
                'nam_xuat_ban' => 'required|digits:4',
                'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
                'gia' => 'nullable|numeric|min:0',
                'trang_thai' => 'required|in:active,inactive',
                'nha_xuat_ban_id' => 'nullable|exists:publishers,id',
                'so_luong' => 'required|integer|min:0',
            ]);

            // Giữ nguyên ảnh cũ nếu không upload ảnh mới
            $path = $book->hinh_anh;
            
            if ($request->hasFile('hinh_anh')) {
                try {
                    // Xóa ảnh cũ nếu có
                    if ($book->hinh_anh && \Storage::disk('public')->exists($book->hinh_anh)) {
                        FileUploadService::deleteFile($book->hinh_anh, 'public');
                    }
                    
                    // Upload ảnh mới sử dụng FileUploadService
                    $result = FileUploadService::uploadImage(
                        $request->file('hinh_anh'),
                        'books',
                        [
                            'max_size' => 2048, // 2MB
                            'resize' => true,
                            'width' => 800,
                            'height' => 800,
                        ]
                    );
                    
                    // Đảm bảo path không rỗng
                    if (empty($result['path'])) {
                        throw new \Exception('Không thể lấy đường dẫn ảnh sau khi upload.');
                    }
                    
                    $path = $result['path'];
                    
                    // Verify file exists after upload
                    if (!\Storage::disk('public')->exists($path)) {
                        throw new \Exception('File ảnh không tồn tại sau khi upload.');
                    }
                    
                    // Log for debugging
                    \Log::info('Image uploaded successfully', [
                        'path' => $path,
                        'filename' => $result['filename'] ?? 'unknown',
                        'url' => $result['url'] ?? 'unknown',
                        'book_id' => $book->id,
                        'file_exists' => \Storage::disk('public')->exists($path),
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error('Upload error:', [
                        'message' => $e->getMessage(),
                        'book_id' => $book->id,
                        'trace' => $e->getTraceAsString(),
                    ]);
                    return redirect()->back()
                        ->withErrors(['hinh_anh' => 'Lỗi khi upload ảnh: ' . $e->getMessage()])
                        ->withInput();
                }
            }
            
            // Log path before saving
            \Log::info('Preparing to save book update', [
                'book_id' => $book->id,
                'has_new_image' => $request->hasFile('hinh_anh'),
                'hinh_anh_path' => $path,
                'path_exists' => $path ? \Storage::disk('public')->exists($path) : false,
            ]);

            $updateData = [
                'ten_sach' => $request->ten_sach,
                'category_id' => $request->category_id,
                'tac_gia' => $request->tac_gia,
                'nam_xuat_ban' => $request->nam_xuat_ban,
                'hinh_anh' => $path, // Ensure path is saved - can be old path or new path
                'mo_ta' => $request->mo_ta,
                'gia' => $request->gia,
                'trang_thai' => $request->trang_thai,
                'so_luong' => $request->so_luong ?? 0,
            ];

            // Thêm nha_xuat_ban_id nếu có trong request
            if ($request->filled('nha_xuat_ban_id')) {
                $updateData['nha_xuat_ban_id'] = $request->nha_xuat_ban_id;
            }

            // Kiểm tra nếu số lượng tăng lên, tạo phiếu nhập kho (chờ duyệt)
            $oldQuantity = $book->so_luong ?? 0;
            $newQuantity = $request->so_luong ?? 0;
            $quantityDifference = $newQuantity - $oldQuantity;

            DB::beginTransaction();
            try {
                // Cập nhật số lượng sách ngay
                $book->update($updateData);

                // Nếu số lượng tăng, tạo phiếu nhập kho (chờ duyệt)
                // Inventory items sẽ được tạo khi duyệt phiếu
                if ($quantityDifference > 0) {
                    // Tạo số phiếu
                    $receiptNumber = InventoryReceipt::generateReceiptNumber();

                    // Lấy vị trí mặc định hoặc vị trí đầu tiên có sẵn
                    $defaultLocation = 'Kệ 1, Tầng 1, Vị trí 1';
                    $existingLocation = Inventory::where('book_id', $book->id)
                        ->where('storage_type', 'Kho')
                        ->first();
                    
                    if ($existingLocation) {
                        $defaultLocation = $existingLocation->location;
                    }

                    // Tạo phiếu nhập kho (chờ duyệt)
                    $receipt = InventoryReceipt::create([
                        'receipt_number' => $receiptNumber,
                        'receipt_date' => now()->toDateString(),
                        'book_id' => $book->id,
                        'quantity' => $quantityDifference,
                        'storage_location' => $defaultLocation,
                        'storage_type' => 'Kho',
                        'unit_price' => $book->gia ?? 0,
                        'total_price' => ($book->gia ?? 0) * $quantityDifference,
                        'supplier' => 'Cập nhật trực tiếp từ quản lý sách',
                        'received_by' => Auth::id(),
                        'approved_by' => null, // Chưa được duyệt
                        'status' => 'pending', // Chờ duyệt
                        'notes' => 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ ' . $oldQuantity . ' lên ' . $newQuantity . '. Vui lòng duyệt phiếu để sách được nhập vào kho.',
                    ]);

                    // KHÔNG tạo Inventory items ngay, chỉ tạo khi duyệt phiếu
                }

                DB::commit();

                // Refresh book model to get latest data including updated image
                $book->refresh();
                
                // Log the updated book data for debugging - always log to help debug
                \Log::info('Book updated successfully', [
                    'book_id' => $book->id,
                    'hinh_anh' => $book->hinh_anh,
                    'had_new_image' => $request->hasFile('hinh_anh'),
                    'image_exists' => $book->hinh_anh ? \Storage::disk('public')->exists($book->hinh_anh) : false,
                    'image_url' => $book->hinh_anh ? \Storage::disk('public')->url($book->hinh_anh) : null,
                    'asset_url' => $book->hinh_anh ? asset('storage/' . ltrim(str_replace('\\', '/', $book->hinh_anh), '/')) : null,
                ]);

                // Log update with old values (array) and description (string)
                AuditService::logUpdated($book, $oldValues, "Book '{$book->ten_sach}' updated");
                
                // Clear dashboard cache
                CacheService::clearDashboard();

                $successMessage = 'Cập nhật sách thành công!';
                if ($quantityDifference > 0) {
                    $successMessage .= ' Đã tạo phiếu nhập kho số lượng ' . $quantityDifference . ' cuốn (chờ duyệt). Vui lòng duyệt phiếu để sách được nhập vào kho.';
                }

                // Redirect back to index page
                return redirect()->route('admin.books.index')->with('success', $successMessage);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Update Book error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return redirect()->back()
                    ->with('error', 'Có lỗi xảy ra khi cập nhật sách: ' . $e->getMessage())
                    ->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Update Book error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->with('error', 'Lỗi khi cập nhật sách: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function hide($id)
    {
        $book = Book::findOrFail($id);
        $book->update(['trang_thai' => 'inactive']);
        
        // Log book hiding
        AuditService::logUpdated($book, "Book '{$book->ten_sach}' hidden");
        
        return redirect()->route('admin.books.index')->with('success', 'Ẩn sách thành công!');
    }

    public function unhide($id)
    {
        $book = Book::findOrFail($id);
        $book->update(['trang_thai' => 'active']);
        
        // Log book unhiding
        AuditService::logUpdated($book, "Book '{$book->ten_sach}' unhidden");
        
        return redirect()->route('admin.books.index')->with('success', 'Hiển thị sách thành công!');
    }

    // Giữ lại method destroy để tương thích (nếu cần)
    public function destroy($id)
    {
        Book::destroy($id);
        return redirect()->route('admin.books.index')->with('success', 'Xóa sách thành công!');
    }

    /**
     * Xóa tất cả sách không còn trong kho
     */
    public function deleteBooksWithoutInventory()
    {
        try {
            DB::beginTransaction();

            // Lấy tất cả book_id có trong inventory
            $bookIdsInInventory = Inventory::select('book_id')
                ->distinct()
                ->pluck('book_id')
                ->toArray();

            // Tìm tất cả Book không có trong inventory
            $query = Book::query();
            if (!empty($bookIdsInInventory)) {
                $query->whereNotIn('id', $bookIdsInInventory);
            }
            
            $booksToDelete = $query->get();
            $count = $booksToDelete->count();

            if ($count == 0) {
                return back()->with('info', 'Không có sách nào cần xóa. Tất cả sách đều có trong kho.');
            }

            // Xóa từng sách và log
            $deletedTitles = [];
            foreach ($booksToDelete as $book) {
                $deletedTitles[] = $book->ten_sach;
                
                // Log trước khi xóa
                \Log::info("Xóa Book #{$book->id} ({$book->ten_sach}) vì không còn trong kho");
                
                // Xóa Book
                $book->delete();
            }

            DB::commit();

            $message = "Đã xóa thành công {$count} sách không còn trong kho!";
            if ($count <= 10) {
                $message .= "\n\nDanh sách sách đã xóa:\n" . implode("\n", $deletedTitles);
            }

            return redirect()->route('admin.books.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete books without inventory error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra khi xóa sách: ' . $e->getMessage());
        }
    }

    /**
     * Sắp xếp lại ID sách để liên tục từ 1
     */
    public function resetIds(Request $request)
    {
        try {
            // Chạy command để sắp xếp lại ID
            \Artisan::call('books:reset-ids', ['--force' => true]);
            
            $output = \Artisan::output();
            
            return redirect()->route('admin.books.index')
                ->with('success', 'Đã sắp xếp lại ID sách thành công! ID hiện tại liên tục từ 1.');
        } catch (\Exception $e) {
            \Log::error('Reset book IDs error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra khi sắp xếp lại ID: ' . $e->getMessage());
        }
    }
}
