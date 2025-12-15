<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'review_id' => $request->review_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Bình luận đã được thêm thành công!');
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        
        // Chỉ cho phép user sở hữu comment hoặc admin chỉnh sửa
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return back()->with('success', 'Bình luận đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Chỉ cho phép user sở hữu comment hoặc admin xóa
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Bình luận đã được xóa thành công!');
    }

    public function like($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->increment('likes_count');

        return response()->json([
            'status' => 'success',
            'likes_count' => $comment->likes_count
        ]);
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Chỉ admin mới có thể duyệt comment
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $comment->update(['is_approved' => true]);

        return back()->with('success', 'Bình luận đã được duyệt thành công!');
    }

    public function reject($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Chỉ admin mới có thể từ chối comment
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $comment->update(['is_approved' => false]);

        return back()->with('success', 'Bình luận đã bị từ chối!');
    }

    // API endpoint để lấy comments của một review
    public function getReviewComments($reviewId)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('review_id', $reviewId)
            ->whereNull('parent_id')
            ->approved()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }

    // API endpoint để tạo comment
    public function createComment(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'review_id' => $request->review_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bình luận đã được thêm thành công!',
            'data' => $comment->load('user')
        ], 201);
    }

    // Public method để tạo comment cho book (tự động tạo review nếu chưa có)
    public function storePublic(Request $request, $bookId)
    {
        $request->validate([
            'content' => 'required|string|max:1500',
        ]);

        // Tìm hoặc tạo review cho book này của user hiện tại
        $review = Review::firstOrCreate(
            [
                'book_id' => $bookId,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => 5, // Rating mặc định
                'comment' => '', // Comment mặc định
                'is_verified' => true,
            ]
        );

        // Tạo comment gắn với review
        $comment = Comment::create([
            'review_id' => $review->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => null,
        ]);

        return back()->with('success', 'Bình luận đã được thêm thành công!');
    }
}