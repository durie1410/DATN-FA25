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
        $user = Auth::user();
        return view('auth.register-reader', compact('user'));
    }

    public function register(Request $request)
    {
        // Nếu user đã đăng nhập → không cần validate email và password unique
        $rules = [
            'name' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nu,Khac',
            'tinh_thanh' => 'required|string',
            'quan_huyen' => 'required|string',
            'phuong_xa' => 'required|string',
            'so_nha' => 'required|string',
        ];

        if (!Auth::check()) {
            // user chưa đăng nhập → cần email và password
            $rules['email'] = 'required|string|email|max:255|unique:users';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Nếu user chưa đăng nhập → tạo User mới
        if (!Auth::check()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            Auth::login($user); // đăng nhập ngay sau khi tạo
        } else {
            // user đã đăng nhập
            $user = Auth::user();
        }

        // Tạo Reader profile
     $reader = Reader::create([
    'user_id' => $user->id,
    'ho_ten' => $request->name,
    'email' => $user->email,
    'so_dien_thoai' => $request->so_dien_thoai,
    'ngay_sinh' => $request->ngay_sinh,
    'gioi_tinh' => $request->gioi_tinh,
    'so_nha' => $request->so_nha,
    'phuong_xa' => $request->phuong_xa,
    'quan_huyen' => $request->quan_huyen,
    'tinh_thanh' => $request->tinh_thanh,
    'so_the_doc_gia' => 'RD' . strtoupper(Str::random(6)),
    'ngay_cap_the' => now(),
    'ngay_het_han' => now()->addYear(),
    'trang_thai' => 'Hoat dong',
]);


        return redirect()->route('home')->with('success', 'Đăng ký độc giả thành công! Bạn có thể mượn sách ngay bây giờ.');
    }
}

















