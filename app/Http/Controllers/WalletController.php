<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Hiển thị trang ví của khách hàng
     */
    public function index()
    {
        $user = auth()->user();
        $user->load('reader');
        
        // Lấy hoặc tạo wallet cho user
        $wallet = Wallet::getOrCreateForUser($user->id);
        
        // Refresh để đảm bảo lấy số dư mới nhất từ database
        $wallet->refresh();
        
        // Lấy các giao dịch gần đây (10 giao dịch)
        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('account.wallet', compact('wallet', 'transactions'));
    }

    /**
     * Hiển thị lịch sử giao dịch đầy đủ
     */
    public function transactions(Request $request)
    {
        $user = auth()->user();
        $user->load('reader');
        
        $wallet = Wallet::getOrCreateForUser($user->id);
        
        // Refresh để đảm bảo lấy số dư mới nhất từ database
        $wallet->refresh();
        
        $query = $wallet->transactions()->orderBy('created_at', 'desc');
        
        // Lọc theo loại giao dịch
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Lọc theo ngày
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $transactions = $query->paginate(20);
        
        return view('account.wallet-transactions', compact('wallet', 'transactions'));
    }
}


