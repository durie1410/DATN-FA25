<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReaderRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register-reader');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'so_dien_thoai' => 'required|string|max:20',
            'so_cccd' => 'required|string|max:20',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nu,Khac',
            'dia_chi' => 'required|string',
        ]);

        // Tạo user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Tạo reader profile
        $reader = Reader::create([
            'user_id' => $user->id,
            'ho_ten' => $request->name,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'so_cccd' => $request->so_cccd,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'dia_chi' => $request->dia_chi,
            'so_the_doc_gia' => 'RD' . strtoupper(Str::random(6)),
            'ngay_cap_the' => now(),
            'ngay_het_han' => now()->addYear(),
            'trang_thai' => 'Hoat dong',
        ]);

        // Đăng nhập user
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký độc giả thành công! Bạn có thể mượn sách ngay bây giờ.');
    }

    /**
     * Hiển thị form đăng ký độc giả cho user đã đăng nhập
     */
    public function showRegistrationFormForUser()
    {
        $user = auth()->user();
        
        // Kiểm tra xem user đã có reader profile chưa
        if ($user->reader) {
            return redirect()->route('account')->with('info', 'Bạn đã có thẻ độc giả rồi!');
        }
        
        return view('account.register-reader', compact('user'));
    }

    /**
     * Xử lý đăng ký độc giả cho user đã đăng nhập
     */
    public function registerForUser(Request $request)
    {
        $user = auth()->user();
        
        // Kiểm tra xem user đã có reader profile chưa
        if ($user->reader) {
            return redirect()->route('account')->with('info', 'Bạn đã có thẻ độc giả rồi!');
        }
        
        $request->validate([
            'so_dien_thoai' => 'required|string|max:20',
            'so_cccd' => 'required|string|max:20',
            'ngay_sinh' => 'required|date|before:today',
            'gioi_tinh' => 'required|in:Nam,Nu,Khac',
            'dia_chi' => 'required|string|max:500',
        ]);

        // Tạo reader profile
        $reader = Reader::create([
            'user_id' => $user->id,
            'ho_ten' => $user->name,
            'email' => $user->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'so_cccd' => $request->so_cccd,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'dia_chi' => $request->dia_chi,
            'so_the_doc_gia' => 'RD' . strtoupper(Str::random(6)),
            'ngay_cap_the' => now(),
            'ngay_het_han' => now()->addYear(),
            'trang_thai' => 'Hoat dong',
        ]);

        // Reload lại user với relationship reader để sidebar hiển thị ngay
        $user->load('reader');

        return redirect()->route('account.purchased-books')->with('success', 'Đăng ký độc giả thành công! Bạn có thể mượn sách ngay bây giờ.');
    }
}


























