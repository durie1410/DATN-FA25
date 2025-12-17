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
        // Load relationship reader để sidebar hiển thị "Sách đang mượn" ngay
        $user->load('reader');
        return view('account', compact('user'));
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function updateAccount(Request $request)
    {
        try {
            $user = auth()->user();
            
            $request->validate([
                'phone' => 'nullable|string|max:20',
                'province' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                'so_cccd' => 'nullable|string|max:20',
            ]);

            // Cập nhật thông tin
            $user->phone = $request->phone ?? null;
            $user->province = $request->province ?? null;
            $user->district = $request->district ?? null;
            $user->address = $request->address ?? null;
            $user->so_cccd = $request->so_cccd ?? null;
            
            if (!$user->save()) {
                return redirect()->route('account')->with('error', 'Không thể cập nhật thông tin. Vui lòng thử lại.');
            }

            return redirect()->route('account')->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            \Log::error('Update account error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('account')
                ->with('error', 'Có lỗi xảy ra khi cập nhật thông tin: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị sách đã mua
     */
    public function purchasedBooks()
    {
        $user = auth()->user();
        
        // Load relationship reader để sidebar hiển thị "Sách đang mượn" ngay
        $user->load('reader');
        
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
        
        // Load relationship reader để sidebar hiển thị "Sách đang mượn" ngay
        $user->load('reader');
        
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
        
        // Load relationship reader để sidebar hiển thị "Sách đang mượn" ngay
        $user->load('reader');
        
        // Lấy Reader của user
        $reader = $user->reader;
        
        // Luôn trả về paginator để tránh lỗi trong view
        if ($reader) {
            // Lấy sách đang mượn (Borrow) qua Reader với trạng thái 'Dang muon'
            $borrows = Borrow::where('reader_id', $reader->id)
                ->where('trang_thai', 'Dang muon')
                ->with(['borrowItems.book', 'borrowItems.inventory', 'librarian', 'reader'])
                ->orderBy('ngay_muon', 'desc')
                ->paginate(12);
            
            // Tính toán lại tien_coc và tien_thue nếu chưa có (từ thông tin sách/inventory)
            foreach ($borrows as $borrow) {
                foreach ($borrow->borrowItems as $item) {
                    // Nếu tien_coc hoặc tien_thue = 0, tính toán lại từ thông tin sách
                    if (($item->tien_coc == 0 || $item->tien_thue == 0) && $item->book && $item->inventory) {
                        $book = $item->book;
                        $inventory = $item->inventory;
                        $hasCard = $reader ? true : false; // Có thẻ độc giả
                        
                        // Sử dụng PricingService để tính phí
                        $fees = \App\Services\PricingService::calculateFees(
                            $book,
                            $inventory,
                            $item->ngay_muon,
                            $item->ngay_hen_tra,
                            $hasCard
                        );
                        
                        $item->tien_coc = $fees['tien_coc'];
                        $item->tien_thue = $fees['tien_thue'];
                        
                        // Lưu lại vào database nếu giá trị đã thay đổi
                        if ($item->isDirty(['tien_coc', 'tien_thue'])) {
                            $item->save();
                            // Tính lại tổng tiền của borrow
                            $borrow->recalculateTotals();
                        }
                    }
                }
            }
            
            // Reservation model đã bị xóa
            $pendingReservations = collect();
        } else {
            // Trả về paginator rỗng nếu không có reader
            $borrows = Borrow::whereRaw('1 = 0')->paginate(12);
            $pendingReservations = collect();
        }

        return view('account.borrowed-books', compact('borrows', 'reader', 'pendingReservations'));
    }

    /**
     * Hiển thị văn bản đã mua
     */
    public function purchasedDocuments()
    {
        $user = auth()->user();
        
        // Load relationship reader để sidebar hiển thị "Sách đang mượn" ngay
        $user->load('reader');
        
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
        $user = auth()->user();
        // Load relationship reader để sidebar hiển thị "Sách đang mượn" ngay
        $user->load('reader');
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

    /**
     * Hiển thị thông tin độc giả
     */
    public function readerInfo()
    {
        $user = auth()->user();
        // Load relationship reader với faculty và department
        $user->load(['reader.faculty', 'reader.department']);
        
        $reader = $user->reader;
        
        return view('account.reader-info', compact('reader'));
    }
}

