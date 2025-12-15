<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Hiển thị trang giỏ hàng
     */
    public function index()
    {
        // Tạm thời redirect về trang sách hoặc hiển thị thông báo
        // Chức năng giỏ hàng mua sách chưa được implement
        return redirect()->route('books.public')->with('info', 'Chức năng giỏ hàng mua sách đang được phát triển. Vui lòng mua trực tiếp từ trang chi tiết sách.');
    }

    /**
     * API: Lấy số lượng items trong giỏ hàng
     */
    public function count()
    {
        return response()->json([
            'count' => 0
        ]);
    }

    /**
     * API: Thêm sách vào giỏ hàng
     */
    public function add(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Chức năng giỏ hàng mua sách chưa được implement'
        ], 400);
    }

    /**
     * API: Cập nhật item trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Chức năng giỏ hàng mua sách chưa được implement'
        ], 400);
    }

    /**
     * API: Xóa item khỏi giỏ hàng
     */
    public function remove($id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Chức năng giỏ hàng mua sách chưa được implement'
        ], 400);
    }

    /**
     * API: Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        return response()->json([
            'success' => false,
            'message' => 'Chức năng giỏ hàng mua sách chưa được implement'
        ], 400);
    }
}

