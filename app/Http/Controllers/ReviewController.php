<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['book', 'user']);

        // Lọc theo sách
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Lọc theo rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Lọc theo trạng thái xác minh
        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra user đã đánh giá sách này chưa
        $existingReview = Review::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->withErrors(['rating' => 'Bạn đã đánh giá sách này rồi.']);
        }

        // Kiểm tra user đã mượn sách này chưa (để xác minh)
        $hasBorrowed = Borrow::where('book_id', $request->book_id)
            ->where('reader_id', function($query) {
                $query->select('id')
                    ->from('readers')
                    ->where('email', Auth::user()->email);
            })
            ->where('trang_thai', 'Da tra')
            ->exists();

        Review::create([
            'book_id' => $request->book_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified' => $hasBorrowed,
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }

    public function show($id)
    {
        $review = Review::with(['book', 'user', 'comments.user'])
            ->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        
        // Chỉ cho phép user sở hữu review hoặc admin chỉnh sửa
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        // Chỉ cho phép user sở hữu review hoặc admin chỉnh sửa
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('admin.reviews.show', $review->id)
            ->with('success', 'Đánh giá đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        // Chỉ cho phép user sở hữu review hoặc admin xóa
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Đánh giá đã được xóa thành công!');
    }

    // API endpoint để lấy đánh giá của một sách
    public function getBookReviews($bookId)
    {
        $reviews = Review::with(['user', 'comments.user'])
            ->where('book_id', $bookId)
            ->verified()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $reviews
        ]);
    }

    // API endpoint để tạo đánh giá
    public function createReview(Request $request)
    {
        // Debug: Log request data
        \Log::info('Review request data:', $request->all());
        
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra user đã đánh giá sách này chưa
        $existingReview = Review::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn đã đánh giá sách này rồi.'
            ], 400);
        }

        // Kiểm tra user đã mượn sách này chưa (để xác minh)
        $hasBorrowed = Borrow::where('book_id', $request->book_id)
            ->where('reader_id', function($query) {
                $query->select('id')
                    ->from('readers')
                    ->where('email', Auth::user()->email);
            })
            ->where('trang_thai', 'Da tra')
            ->exists();

        $review = Review::create([
            'book_id' => $request->book_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified' => $hasBorrowed,
        ]);

        // Debug: Log created review
        \Log::info('Created review:', $review->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Đánh giá đã được gửi thành công!',
            'data' => $review->load('user')
        ], 201);
    }
}