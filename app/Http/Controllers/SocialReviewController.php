<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class SocialReviewController extends Controller
{
    /**
     * Display reviews for a book
     */
    public function index(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);
        
        $reviews = Review::with(['user', 'book'])
            ->where('book_id', $bookId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate average rating
        $averageRating = Review::where('book_id', $bookId)
            ->where('status', 'approved')
            ->avg('rating');

        // Rating distribution
        $ratingDistribution = Review::where('book_id', $bookId)
            ->where('status', 'approved')
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Check if user has reviewed this book
        $userReview = null;
        if (Auth::check()) {
            $userReview = Review::where('book_id', $bookId)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('social.reviews.index', compact(
            'book', 'reviews', 'averageRating', 'ratingDistribution', 'userReview'
        ));
    }

    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'title' => 'nullable|string|max:255',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Check if user has already reviewed this book
        $existingReview = Review::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->withErrors(['error' => 'Bạn đã đánh giá cuốn sách này rồi.']);
        }

        // Check if user has borrowed this book
        $hasBorrowed = Auth::user()->borrows()
            ->where('book_id', $request->book_id)
            ->where('trang_thai', 'Da tra')
            ->exists();

        if (!$hasBorrowed) {
            return back()->withErrors(['error' => 'Bạn cần mượn và trả sách trước khi đánh giá.']);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'title' => $request->title,
            'status' => 'pending', // Require approval
        ]);

        // Log the review creation
        AuditService::logCreated($review, 'User created book review');

        return back()->with('success', 'Đánh giá của bạn đã được gửi và đang chờ phê duyệt.');
    }

    /**
     * Update user's review
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'title' => 'nullable|string|max:255',
        ]);

        $oldValues = $review->toArray();

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'title' => $request->title,
            'status' => 'pending', // Require re-approval
        ]);

        // Log the review update
        AuditService::logUpdated($review, $oldValues, 'User updated book review');

        return back()->with('success', 'Đánh giá đã được cập nhật và đang chờ phê duyệt.');
    }

    /**
     * Delete user's review
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa đánh giá này.');
        }

        // Log the review deletion
        AuditService::logDeleted($review, 'User deleted book review');

        $review->delete();

        return back()->with('success', 'Đánh giá đã được xóa.');
    }

    /**
     * Like/Unlike a review
     * NOTE: This functionality has been removed as review_likes table is no longer used
     */
    // public function toggleLike(Request $request, $id)
    // {
    //     // Functionality removed - review_likes table has been deleted
    // }

    /**
     * Report a review
     */
    public function report(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $review = Review::findOrFail($id);

        // Check if user has already reported this review
        $existingReport = $review->reports()->where('user_id', Auth::id())->first();

        if ($existingReport) {
            return back()->withErrors(['error' => 'Bạn đã báo cáo đánh giá này rồi.']);
        }

        $review->reports()->create([
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Báo cáo đã được gửi. Chúng tôi sẽ xem xét và xử lý.');
    }

    /**
     * Get reviews for API
     */
    public function apiIndex(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);
        
        $reviews = Review::with(['user:id,name', 'book:id,ten_sach'])
            ->where('book_id', $bookId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Add like count and user's like status (removed - review_likes table deleted)
        $reviews->getCollection()->transform(function ($review) {
            $review->like_count = 0; // review_likes functionality removed
            $review->user_liked = false;
            return $review;
        });

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Get user's reviews
     */
    public function userReviews(Request $request)
    {
        $reviews = Review::with(['book:id,ten_sach,hinh_anh'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('social.reviews.user-reviews', compact('reviews'));
    }

    /**
     * Get popular reviews
     */
    public function popular(Request $request)
    {
        $reviews = Review::with(['user:id,name', 'book:id,ten_sach,hinh_anh'])
            ->where('status', 'approved')
            // ->withCount('likes') // Removed - review_likes table deleted
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('social.reviews.popular', compact('reviews'));
    }

    /**
     * Share review
     */
    public function share(Request $request, $id)
    {
        $review = Review::with(['book', 'user'])->findOrFail($id);
        
        $shareData = [
            'title' => "Đánh giá sách: {$review->book->ten_sach}",
            'description' => $review->comment,
            'url' => route('reviews.show', $review->id),
            'image' => $review->book->hinh_anh ? asset('storage/' . $review->book->hinh_anh) : null,
        ];

        return response()->json([
            'success' => true,
            'data' => $shareData
        ]);
    }
}















