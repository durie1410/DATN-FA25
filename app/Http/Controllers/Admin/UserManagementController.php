<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Librarian;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Dashboard tổng hợp quản lý người dùng và thủ thư
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
        
        
        // Thống kê thủ thư
        $totalLibrarians = Librarian::count();
        $activeLibrarians = Librarian::where('trang_thai', 'active')->count();
        $inactiveLibrarians = Librarian::where('trang_thai', 'inactive')->count();
        $expiringContracts = Librarian::where('ngay_het_han_hop_dong', '<=', now()->addDays(30))
            ->where('ngay_het_han_hop_dong', '>', now())
            ->count();
        
        // Query cho danh sách
        $userQuery = User::query();
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
        $librarians = $librarianQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'librarians_page');
        
        return view('admin.user-management.dashboard', compact(
            'totalUsers',
            'adminUsers',
            'regularUsers',
            'newUsersThisMonth',
            'newUsersThisWeek',
            'totalLibrarians',
            'activeLibrarians',
            'inactiveLibrarians',
            'expiringContracts',
            'users',
            'librarians'
        ));
    }
}

