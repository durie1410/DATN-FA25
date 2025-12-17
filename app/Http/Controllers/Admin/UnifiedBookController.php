<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\PurchasableBook;
use Illuminate\Http\Request;

class UnifiedBookController extends Controller
{
    public function index(Request $request)
    {
        // Lấy sách mượn với phân trang
        $borrowBooks = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'borrow_page');

        // Lấy sách mua với phân trang
        $purchasableBooks = PurchasableBook::orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'purchase_page');

        return view('admin.books.unified', compact('borrowBooks', 'purchasableBooks'));
    }
}

