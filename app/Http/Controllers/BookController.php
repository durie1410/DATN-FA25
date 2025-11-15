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

        // Lấy danh sách sách sau khi lọc với phân trang
        $books = $query->orderBy('id', 'asc')->paginate(10);

        // Lấy danh sách thể loại để hiển thị dropdown (cached)
        $categories = CacheService::getCategories();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function show($id)
    {
        $book = Book::with([
            'category',
            'reviews.user',
            'reviews.comments.user',
            'borrows.reader',
            'inventories',
            'favorites.user'
        ])->findOrFail($id);

        // Lấy sách liên quan (cùng thể loại)
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->with('category')
            ->limit(4)
            ->get();

        // Thống kê sách
        $stats = [
            'total_reviews' => $book->reviews()->count(),
            'average_rating' => $book->reviews()->avg('rating') ?? 0,
            'total_borrows' => $book->borrows()->count(),
            'total_favorites' => $book->favorites()->count(),
            'total_copies' => $book->inventories()->count(),
            'available_copies' => $book->inventories()->where('status', 'Co san')->count(),
            'borrowed_copies' => $book->inventories()->where('status', 'Dang muon')->count(),
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

        return view('admin.books.show', compact('book', 'relatedBooks', 'stats', 'isFavorited', 'userReview'));
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

            $path = $book->hinh_anh;
            
            if ($request->hasFile('hinh_anh')) {
                try {
                    // Xóa ảnh cũ nếu có
                    if ($book->hinh_anh) {
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
                    $path = $result['path'];
                    
                    // Log for debugging
                    \Log::info('Image uploaded successfully', [
                        'path' => $path,
                        'filename' => $result['filename'] ?? 'unknown',
                        'url' => $result['url'] ?? 'unknown',
                        'book_id' => $book->id,
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error('Upload error:', ['message' => $e->getMessage()]);
                    return redirect()->back()
                        ->withErrors(['hinh_anh' => $e->getMessage()])
                        ->withInput();
                }
            }
            
            // Log path before saving
            if ($request->hasFile('hinh_anh')) {
                \Log::info('Saving image path to database', [
                    'path' => $path,
                    'book_id' => $book->id,
                    'exists' => \Storage::disk('public')->exists($path),
                ]);
            }

            $updateData = [
                'ten_sach' => $request->ten_sach,
                'category_id' => $request->category_id,
                'tac_gia' => $request->tac_gia,
                'nam_xuat_ban' => $request->nam_xuat_ban,
                'hinh_anh' => $path,
                'mo_ta' => $request->mo_ta,
                'gia' => $request->gia,
                'trang_thai' => $request->trang_thai,
                'so_luong' => $request->so_luong ?? 0,
            ];

            // Thêm nha_xuat_ban_id nếu có trong request
            if ($request->filled('nha_xuat_ban_id')) {
                $updateData['nha_xuat_ban_id'] = $request->nha_xuat_ban_id;
            }

            // Kiểm tra nếu số lượng tăng lên, tạo phiếu nhập kho tự động (chờ phê duyệt)
            $oldQuantity = $book->so_luong ?? 0;
            $newQuantity = $request->so_luong ?? 0;
            $quantityDifference = $newQuantity - $oldQuantity;

            DB::beginTransaction();
            try {
                // Không cập nhật số lượng sách ngay, chỉ tạo phiếu nhập kho
                // Số lượng sẽ được cập nhật khi duyệt phiếu nhập kho
                unset($updateData['so_luong']);
                $book->update($updateData);

                // Nếu số lượng tăng, tạo phiếu nhập kho tự động (chờ phê duyệt)
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

                    // Tạo phiếu nhập kho tự động (chờ phê duyệt)
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
                        'status' => 'pending', // Chờ phê duyệt
                        'notes' => 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ ' . $oldQuantity . ' lên ' . $newQuantity . '. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.',
                    ]);

                    // KHÔNG tạo Inventory items ngay, chỉ tạo khi duyệt phiếu
                }

                DB::commit();

                // Log update with old values (array) and description (string)
                AuditService::logUpdated($book, $oldValues, "Book '{$book->ten_sach}' updated");
                
                // Clear dashboard cache
                CacheService::clearDashboard();

                $successMessage = 'Cập nhật sách thành công!';
                if ($quantityDifference > 0) {
                    $successMessage .= ' Đã tạo phiếu nhập kho số lượng ' . $quantityDifference . ' cuốn (chờ phê duyệt). Vui lòng duyệt phiếu nhập kho để số lượng sách được cập nhật vào kho.';
                }

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

    public function testUpload(Request $request)
    {
        try {
            if ($request->hasFile('test_file')) {
                $file = $request->file('test_file');
                
                // Kiểm tra file
                if (!$file->isValid()) {
                    return response()->json(['success' => false, 'error' => 'File không hợp lệ']);
                }
                
                // Tạo tên file
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Lưu file
                $path = $file->storeAs('books', $filename, 'public');
                
                return response()->json([
                    'success' => true, 
                    'path' => $path,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Không có file được upload']);
        } catch (\Exception $e) {
            \Log::error('Test upload error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
