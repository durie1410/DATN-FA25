<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Inventory;
use App\Services\CacheService;
use App\Services\FileUploadService;

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
                $query->whereIn('id', $bookIdsInInventory);
            } elseif ($request->inventory_status === 'no_inventory') {
                if (!empty($bookIdsInInventory)) {
                    $query->whereNotIn('id', $bookIdsInInventory);
                }
            }
        }

        $books = $query->orderBy('id', 'asc')->paginate(10);
        $categories = CacheService::getCategories();
        
        $totalBooks = Book::count();
        $bookIdsInInventory = Inventory::select('book_id')->distinct()->pluck('book_id')->toArray();
        $booksWithInventory = count($bookIdsInInventory);
        $booksWithoutInventory = $totalBooks - $booksWithInventory;

        return view('staff.books.index', compact('books', 'categories', 'totalBooks', 'booksWithInventory', 'booksWithoutInventory'));
    }

    public function create()
    {
        $categories = CacheService::getActiveCategories();
        $publishers = \App\Models\Publisher::where('trang_thai', 'active')->orderBy('ten_nha_xuat_ban')->get();
        return view('staff.books.create', compact('categories', 'publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_sach' => 'required|string|max:255',
            'tac_gia' => 'required|string|max:255',
            'nam_xuat_ban' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'mo_ta' => 'nullable|string',
            'gia' => 'nullable|numeric|min:0',
            'trang_thai' => 'required|in:active,inactive',
            'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'nha_xuat_ban_id' => 'nullable|exists:publishers,id',
        ]);

        $path = null;
        if ($request->hasFile('hinh_anh')) {
            try {
                $result = FileUploadService::uploadImage(
                    $request->file('hinh_anh'),
                    'books',
                    [
                        'max_size' => 2048,
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
                'gia' => $request->gia ?? 0,
                'trang_thai' => $request->trang_thai,
                'danh_gia_trung_binh' => 0,
                'so_luong_ban' => 0,
                'so_luot_xem' => 0,
            ]);

            return redirect()->route('staff.books.index')
                ->with('success', 'Thêm sách thành công!');
        } catch (\Exception $e) {
            \Log::error('Book creation error:', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Lỗi khi tạo sách: ' . $e->getMessage())
                ->withInput();
        }
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

        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->with('category')
            ->limit(4)
            ->get();

        $borrowItems = \App\Models\BorrowItem::where('book_id', $book->id)
            ->with(['borrow.reader', 'borrow.librarian', 'inventory'])
            ->orderBy('ngay_muon', 'desc')
            ->get();

        $stats = [
            'total_reviews' => $book->reviews()->count(),
            'average_rating' => $book->reviews()->avg('rating') ?? 0,
            'total_favorites' => $book->favorites()->count(),
            'total_copies' => $book->inventories()->count(),
            'available_copies' => $book->inventories()->where('status', 'Co san')->count(),
            'borrowed_copies' => $book->inventories()->where('status', 'Dang muon')->count(),
            'total_borrows' => $borrowItems->count(),
        ];

        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = $book->favorites()->where('user_id', auth()->id())->exists();
        }

        $userReview = null;
        if (auth()->check()) {
            $userReview = $book->reviews()->where('user_id', auth()->id())->first();
        }

        return view('staff.books.show', compact('book', 'relatedBooks', 'stats', 'isFavorited', 'userReview', 'borrowItems'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = CacheService::getActiveCategories();
        $publishers = \App\Models\Publisher::where('trang_thai', 'active')->orderBy('ten_nha_xuat_ban')->get();
        return view('staff.books.edit', compact('book', 'categories', 'publishers'));
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);

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
                    if ($book->hinh_anh && \Storage::disk('public')->exists($book->hinh_anh)) {
                        FileUploadService::deleteFile($book->hinh_anh, 'public');
                    }
                    
                    $result = FileUploadService::uploadImage(
                        $request->file('hinh_anh'),
                        'books',
                        [
                            'max_size' => 2048,
                            'resize' => true,
                            'width' => 800,
                            'height' => 800,
                        ]
                    );
                    
                    if (empty($result['path'])) {
                        throw new \Exception('Không thể lấy đường dẫn ảnh sau khi upload.');
                    }
                    
                    $path = $result['path'];
                    
                } catch (\Exception $e) {
                    \Log::error('Upload error:', ['message' => $e->getMessage()]);
                    return redirect()->back()
                        ->withErrors(['hinh_anh' => 'Lỗi khi upload ảnh: ' . $e->getMessage()])
                        ->withInput();
                }
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

            if ($request->filled('nha_xuat_ban_id')) {
                $updateData['nha_xuat_ban_id'] = $request->nha_xuat_ban_id;
            }

            $book->update($updateData);

            return redirect()->route('staff.books.index')->with('success', 'Cập nhật sách thành công!');
        } catch (\Exception $e) {
            \Log::error('Update Book error:', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Lỗi khi cập nhật sách: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function hide($id)
    {
        $book = Book::findOrFail($id);
        $book->update(['trang_thai' => 'inactive']);
        return redirect()->route('staff.books.index')->with('success', 'Ẩn sách thành công!');
    }

    public function unhide($id)
    {
        $book = Book::findOrFail($id);
        $book->update(['trang_thai' => 'active']);
        return redirect()->route('staff.books.index')->with('success', 'Hiển thị sách thành công!');
    }
}
