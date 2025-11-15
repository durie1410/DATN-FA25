<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PurchasableBook;
use App\Models\Borrow;
use App\Models\Book;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserAccountController extends Controller
{
    /**
     * Hiển thị trang thông tin tài khoản
     */
    public function account()
    {
        $user = auth()->user();
        return view('account', compact('user'));
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function updateAccount(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        // Cập nhật thông tin (cần thêm các trường này vào migration nếu chưa có)
        $user->phone = $request->phone;
        $user->province = $request->province;
        $user->district = $request->district;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('account')->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Hiển thị sách đã mua
     */
    public function purchasedBooks()
    {
        $user = auth()->user();
        
        // Lấy các OrderItem từ các đơn hàng đã thanh toán của user
        $orderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('payment_status', ['paid'])
                  ->whereIn('status', ['processing', 'shipped', 'delivered']);
        })
        ->with(['purchasableBook', 'order'])
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('account.purchased-books', compact('orderItems'));
    }

    /**
     * Hiển thị sách đang đọc
     */
    public function readingBooks()
    {
        $user = auth()->user();
        
        // Lấy Reader của user
        $reader = $user->reader;
        $borrowedBooks = collect();
        
        if ($reader) {
            // Lấy sách đang mượn (Borrow) qua Reader
            $borrowedBooks = Borrow::where('reader_id', $reader->id)
                ->where('trang_thai', 'Dang muon')
                ->with('book')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Lấy sách đã mua (có thể đọc)
        $purchasedBooks = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('payment_status', ['paid']);
        })
        ->with('purchasableBook')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('account.reading-books', compact('borrowedBooks', 'purchasedBooks'));
    }

    /**
     * Hiển thị sách đang mượn
     */
    public function borrowedBooks()
    {
        $user = auth()->user();
        
        // Lấy Reader của user
        $reader = $user->reader;
        
        // Luôn trả về paginator để tránh lỗi trong view
        if ($reader) {
            // Lấy sách đang mượn (Borrow) qua Reader với trạng thái 'Dang muon'
            $borrows = Borrow::where('reader_id', $reader->id)
                ->where('trang_thai', 'Dang muon')
                ->with(['book', 'librarian'])
                ->orderBy('ngay_muon', 'desc')
                ->paginate(12);
        } else {
            // Trả về paginator rỗng nếu không có reader
            $borrows = Borrow::whereRaw('1 = 0')->paginate(12);
        }

        return view('account.borrowed-books', compact('borrows', 'reader'));
    }

    /**
     * Hiển thị văn bản đã mua
     */
    public function purchasedDocuments()
    {
        $user = auth()->user();
        
        // Lấy các văn bản từ OrderItems (giả sử có thể có Document trong OrderItems)
        // Hoặc có thể có bảng riêng cho documents
        $documents = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('payment_status', ['paid']);
        })
        ->whereHas('purchasableBook', function($query) {
            // Có thể filter theo loại document nếu có
        })
        ->with(['purchasableBook', 'order'])
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('account.purchased-documents', compact('documents'));
    }

    /**
     * Hiển thị form đổi mật khẩu
     */
    public function showChangePassword()
    {
        return view('account.change-password');
    }

    /**
     * Xử lý đổi mật khẩu
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.change-password')->with('success', 'Đổi mật khẩu thành công!');
    }
}

