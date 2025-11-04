<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicBookController extends Controller
{
    public function show($id)
    {
        $book = Book::with([
            'category',
            'reviews' => function($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            },
            'reviews.comments.user',
            'inventories'
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

        return view('books.show', compact('book', 'relatedBooks', 'stats', 'isFavorited', 'userReview'));
    }

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
        $books = $query->orderBy('created_at', 'desc')->paginate(12);

        // Lấy danh sách thể loại để hiển thị dropdown
        $categories = Category::all();

        return view('books.public', compact('books', 'categories'));
    }
}
