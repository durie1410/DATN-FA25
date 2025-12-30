<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
            'so_cccd' => 'required|string|max:20|unique:readers,so_cccd',
            'ngay_sinh' => 'required|date|before:today',
            'gioi_tinh' => 'required|in:Nam,Nu,Khac',
            'dia_chi' => 'required|string|max:500',
        ], [
            'name.required' => 'Họ và tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_cccd.required' => 'Số CCCD là bắt buộc.',
            'so_cccd.unique' => 'Số CCCD đã được sử dụng.',
            'ngay_sinh.required' => 'Ngày sinh là bắt buộc.',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng.',
            'ngay_sinh.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'gioi_tinh.required' => 'Giới tính là bắt buộc.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',
            'dia_chi.required' => 'Địa chỉ là bắt buộc.',
        ]);

        try {
            DB::beginTransaction();

            // Tạo user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            // Tạo số thẻ độc giả unique
            $soTheDocGia = $this->generateUniqueReaderCardNumber();

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
                'so_the_doc_gia' => $soTheDocGia,
                'ngay_cap_the' => now(),
                'ngay_het_han' => now()->addYear(),
                'trang_thai' => 'Hoat dong',
            ]);

            DB::commit();

            // Đăng nhập user
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Đăng ký độc giả thành công! Bạn có thể mượn sách ngay bây giờ.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Lỗi đăng ký độc giả: ' . $e->getMessage(), [
                'request' => $request->except(['password', 'password_confirmation']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['error' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại sau.']);
        }
    }

    /**
     * Tạo số thẻ độc giả unique
     */
    private function generateUniqueReaderCardNumber()
    {
        $maxAttempts = 10;
        $attempt = 0;

        do {
            $soTheDocGia = 'RD' . strtoupper(Str::random(6));
            $exists = Reader::where('so_the_doc_gia', $soTheDocGia)->exists();
            $attempt++;
        } while ($exists && $attempt < $maxAttempts);

        // Nếu vẫn trùng sau 10 lần, thêm timestamp để đảm bảo unique
        if ($exists) {
            $soTheDocGia = 'RD' . strtoupper(Str::random(4)) . time();
        }

        return $soTheDocGia;
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
            'so_cccd' => 'required|string|max:20|unique:readers,so_cccd',
            'ngay_sinh' => 'required|date|before:today',
            'gioi_tinh' => 'required|in:Nam,Nu,Khac',
            'dia_chi' => 'required|string|max:500',
        ], [
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_cccd.required' => 'Số CCCD là bắt buộc.',
            'so_cccd.unique' => 'Số CCCD đã được sử dụng.',
            'ngay_sinh.required' => 'Ngày sinh là bắt buộc.',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng.',
            'ngay_sinh.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'gioi_tinh.required' => 'Giới tính là bắt buộc.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',
            'dia_chi.required' => 'Địa chỉ là bắt buộc.',
        ]);

        try {
            DB::beginTransaction();

            // Tạo số thẻ độc giả unique
            $soTheDocGia = $this->generateUniqueReaderCardNumber();

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
                'so_the_doc_gia' => $soTheDocGia,
                'ngay_cap_the' => now(),
                'ngay_het_han' => now()->addYear(),
                'trang_thai' => 'Hoat dong',
            ]);

            DB::commit();

            // Reload lại user với relationship reader để sidebar hiển thị ngay
            $user->load('reader');

            return redirect()->route('account.purchased-books')->with('success', 'Đăng ký độc giả thành công! Bạn có thể mượn sách ngay bây giờ.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Lỗi đăng ký độc giả cho user đã đăng nhập: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request' => $request->except(['password', 'password_confirmation']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['error' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại sau.']);
        }
    }
}


























