<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reader;
use App\Models\Librarian;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Dashboard tổng hợp quản lý người dùng, độc giả và thủ thư
     */
    public function dashboard(Request $request)
    {
        // Thống kê người dùng
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $regularUsers = User::where('role', 'user')->count();
        
        // Thống kê người dùng mới trong tháng
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Thống kê người dùng mới trong tuần
        $newUsersThisWeek = User::where('created_at', '>=', now()->startOfWeek())
            ->count();
        
        // Thống kê độc giả
        $totalReaders = Reader::count();
        $activeReaders = Reader::where('trang_thai', 'Hoat dong')->count();
        $suspendedReaders = Reader::where('trang_thai', 'Tam khoa')->count();
        $expiredReaders = Reader::where('trang_thai', 'Het han')->count();
        
        // Thống kê độc giả mới trong tháng
        $newReadersThisMonth = Reader::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Thống kê thủ thư
        $totalLibrarians = Librarian::count();
        $activeLibrarians = Librarian::where('trang_thai', 'active')->count();
        $inactiveLibrarians = Librarian::where('trang_thai', 'inactive')->count();
        $expiringContracts = Librarian::where('ngay_het_han_hop_dong', '<=', now()->addDays(30))
            ->where('ngay_het_han_hop_dong', '>', now())
            ->count();
        
        // Query cho danh sách
        $userQuery = User::query();
        $readerQuery = Reader::query();
        $librarianQuery = Librarian::with('user');
        
        // Filter users
        if ($request->filled('user_search')) {
            $search = $request->get('user_search');
            $userQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('user_role')) {
            $userQuery->where('role', $request->get('user_role'));
        }
        
        // Filter readers
        if ($request->filled('reader_search')) {
            $search = $request->get('reader_search');
            $readerQuery->where(function($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('so_the_doc_gia', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('reader_status')) {
            $readerQuery->where('trang_thai', $request->get('reader_status'));
        }
        
        // Filter librarians
        if ($request->filled('librarian_search')) {
            $search = $request->get('librarian_search');
            $librarianQuery->where(function($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ma_thu_thu', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('librarian_status')) {
            $librarianQuery->where('trang_thai', $request->get('librarian_status'));
        }
        
        $users = $userQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'users_page');
        $readers = $readerQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'readers_page');
        $librarians = $librarianQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'librarians_page');
        
        return view('admin.user-management.dashboard', compact(
            'totalUsers',
            'adminUsers',
            'regularUsers',
            'newUsersThisMonth',
            'newUsersThisWeek',
            'totalReaders',
            'activeReaders',
            'suspendedReaders',
            'expiredReaders',
            'newReadersThisMonth',
            'totalLibrarians',
            'activeLibrarians',
            'inactiveLibrarians',
            'expiringContracts',
            'users',
            'readers',
            'librarians'
        ));
    }
}

