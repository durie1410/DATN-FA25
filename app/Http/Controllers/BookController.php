<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Http\Requests\BookRequest;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // Lấy danh sách thể loại để hiển thị dropdown
        $categories = Category::all();

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
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(BookRequest $request)
    {
        // Validation đã được xử lý trong BookRequest

        $path = null;
        if ($request->hasFile('hinh_anh')) {
            try {
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/books');
                
                // Đảm bảo thư mục tồn tại
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $filename);
                $path = 'books/' . $filename;
            } catch (\Exception $e) {
                \Log::error('Upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Lỗi khi upload ảnh: ' . $e->getMessage());
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
                'dinh_dang' => $request->dinh_dang,
                'trang_thai' => $request->trang_thai,
                'danh_gia_trung_binh' => 0,
                'so_luong_ban' => 0,
                'so_luot_xem' => 0,
            ]);

            // Log book creation
            AuditService::logCreated($book, "Book '{$book->ten_sach}' created");

            return redirect()->route('admin.books.index')->with('success', 'Thêm sách thành công!');
        } catch (\Exception $e) {
            \Log::error('Create Book error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Lỗi khi tạo sách: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'ten_sach' => 'required|max:255',
            'category_id' => 'required',
            'tac_gia' => 'required',
            'nam_xuat_ban' => 'required|digits:4',
            'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'gia' => 'nullable|numeric|min:0',
            'dinh_dang' => 'required',
            'trang_thai' => 'required|in:active,inactive',
        ]);

        $path = $book->hinh_anh;
        
        if ($request->hasFile('hinh_anh')) {
            try {
                // Xóa ảnh cũ nếu có
                if ($book->hinh_anh && Storage::disk('public')->exists($book->hinh_anh)) {
                    Storage::disk('public')->delete($book->hinh_anh);
                }
                
                // Lưu ảnh mới
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/books');
                
                // Đảm bảo thư mục tồn tại
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $filename);
                $path = 'books/' . $filename;
                
            } catch (\Exception $e) {
                \Log::error('Upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Lỗi khi upload ảnh: ' . $e->getMessage());
            }
        }

        $book->update([
            'ten_sach' => $request->ten_sach,
            'category_id' => $request->category_id,
            'tac_gia' => $request->tac_gia,
            'nam_xuat_ban' => $request->nam_xuat_ban,
            'hinh_anh' => $path,
            'mo_ta' => $request->mo_ta,
            'gia' => $request->gia,
            'dinh_dang' => $request->dinh_dang,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Cập nhật sách thành công!');
    }

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
