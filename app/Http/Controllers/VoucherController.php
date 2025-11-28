<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Hiển thị danh sách voucher
     */
    public function index()
    {
        $vouchers = Voucher::withTrashed()->paginate(10); // lấy cả đã xoá
        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Hiển thị form tạo voucher
     */
    public function create()
    {
        $readers = \App\Models\Reader::all();
        return view('admin.vouchers.create', compact('readers'));
    }

    /**
     * Lưu voucher mới
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'reader_id' => 'required|exists:readers,id', // nếu voucher có thể gắn độc giả
        'ma' => 'required|string|max:255|unique:vouchers,ma',
        'loai' => 'required|in:percentage,fixed',
        'gia_tri' => 'required|numeric|min:0',
        'so_luong' => 'required|integer|min:0',
        'mo_ta' => 'nullable|string',
        'don_toi_thieu' => 'required|numeric|min:0',
        'ngay_bat_dau' => 'required|date',
        'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
    ],[
       'reader_id.required'=> 'Vui lòng nhập  thành viên.',
    'ma.required' => 'Vui lòng nhập mã giảm giá.',
    'ma.unique' => 'Mã giảm giá đã tồn tại.',
    'loai.required' => 'Vui lòng chọn loại giảm giá.',
    'loai.in' => 'Loại giảm giá không hợp lệ.',
    'gia_tri.required' => 'Vui lòng nhập giá trị giảm.',
    'gia_tri.numeric' => 'Giá trị giảm phải là số.',
    'so_luong.required' => 'Vui lòng nhập số lượng mã.',
    'so_luong.integer' => 'Số lượng phải là số nguyên.',
    'ngay_bat_dau.required'=> 'chọn ngày bắt đầu.',
    'ngay_ket_thuc.required'=> 'chọn ngày kết thúc.',

    'don_toi_thieu.required' => 'Vui lòng nhập giá trị đơn tối thiểu.',
    'don_toi_thieu.numeric' => 'Giá trị đơn tối thiểu phải là số.',
    'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
]);

    Voucher::create($validated + [
        'kich_hoat' => 1,
        'trang_thai' => 'active',
    ]);

    return redirect()->route('admin.vouchers.index')->with('success', 'Tạo mã giảm giá thành công!');
}

    /**
     * Hiển thị form sửa voucher
     */
    public function edit($id)
    {
        $voucher = Voucher::withTrashed()->findOrFail($id);
        $readers = \App\Models\Reader::all();
        return view('admin.vouchers.edit', compact('voucher', 'readers'));
    }

    /**
     * Cập nhật voucher
     */
public function update(Request $request, $id)
{
    $voucher = Voucher::withTrashed()->findOrFail($id);

    $validated = $request->validate([
        'reader_id' => 'required|exists:readers,id',
        'ma' => 'required|string|max:255|unique:vouchers,ma,' . $voucher->id,
        'loai' => 'required|in:percentage,fixed',
        'gia_tri' => 'required|numeric|min:0',
        'so_luong' => 'required|integer|min:0',
        'don_toi_thieu' => 'required|numeric|min:0',
        'ngay_bat_dau' => 'nullable|date',
        'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
        'kich_hoat' => 'required|in:0,1',
        'trang_thai' => 'required|in:active,inactive,expired',
    ], [
        'reader_id.required' => 'Vui lòng nhập thành viên.',
        'ma.required' => 'Vui lòng nhập mã giảm giá.',
        'ma.unique' => 'Mã giảm giá đã tồn tại.',
        'loai.required' => 'Vui lòng chọn loại giảm giá.',
        'gia_tri.required' => 'Vui lòng nhập giá trị giảm.',
        'so_luong.required' => 'Vui lòng nhập số lượng.',
        'don_toi_thieu.required' => 'Vui lòng nhập giá trị đơn tối thiểu.',
        'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        'kich_hoat.required' => 'Vui lòng chọn trạng thái kích hoạt.',
        'trang_thai.required' => 'Vui lòng chọn trạng thái voucher.',
    ]);

    $voucher->update(array_merge($validated, [
        'kich_hoat' => (int)$validated['kich_hoat'],
    ]));

    return redirect()->route('admin.vouchers.index')
        ->with('success', 'Cập nhật mã giảm giá thành công!');
}


    /**
     * Xoá mềm voucher
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Đã xoá tạm thời mã giảm giá.');
    }

    /**
     * Khôi phục voucher đã xoá
     */
    public function restore($id)
    {
        $voucher = Voucher::withTrashed()->findOrFail($id);
        $voucher->restore();

        return redirect()->route('admin.vouchers.index')->with('success', 'Đã khôi phục mã giảm giá!');
    }

    /**
     * Xoá vĩnh viễn
     */
    public function forceDelete($id)
    {
        $voucher = Voucher::withTrashed()->findOrFail($id);
        $voucher->forceDelete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Đã xoá vĩnh viễn mã giảm giá!');
    }
}
