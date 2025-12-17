<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReaderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $readerId = $this->route('reader') ? $this->route('reader')->id : null;

        return [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:readers,email,' . $readerId,
            'so_dien_thoai' => 'required|string|regex:/^[0-9+\-\s()]+$/|min:10|max:15',
            'so_cccd' => 'required|string|max:20',
            'ngay_sinh' => 'required|date|before:today|after:1900-01-01',
            'gioi_tinh' => 'required|in:Nam,Nu,Khac',
            'dia_chi' => 'required|string|max:500',
            'faculty_id' => 'nullable|exists:faculties,id',
            'department_id' => 'nullable|exists:departments,id',
            'so_the_doc_gia' => 'required|string|max:20|unique:readers,so_the_doc_gia,' . $readerId,
            'ngay_cap_the' => 'required|date|before_or_equal:today',
            'ngay_het_han' => 'required|date|after:ngay_cap_the',
            'trang_thai' => 'required|in:Hoat dong,Tam dung,Bi khoa',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ho_ten.required' => 'Họ tên là bắt buộc.',
            'ho_ten.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng.',
            'so_dien_thoai.min' => 'Số điện thoại phải có ít nhất 10 ký tự.',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'so_cccd.required' => 'Số CCCD là bắt buộc.',
            'so_cccd.max' => 'Số CCCD không được vượt quá 20 ký tự.',
            'ngay_sinh.required' => 'Ngày sinh là bắt buộc.',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng.',
            'ngay_sinh.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'ngay_sinh.after' => 'Ngày sinh không hợp lệ.',
            'gioi_tinh.required' => 'Giới tính là bắt buộc.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',
            'dia_chi.required' => 'Địa chỉ là bắt buộc.',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'faculty_id.exists' => 'Khoa không tồn tại.',
            'department_id.exists' => 'Bộ môn không tồn tại.',
            'so_the_doc_gia.required' => 'Số thẻ độc giả là bắt buộc.',
            'so_the_doc_gia.unique' => 'Số thẻ độc giả đã được sử dụng.',
            'ngay_cap_the.required' => 'Ngày cấp thẻ là bắt buộc.',
            'ngay_cap_the.before_or_equal' => 'Ngày cấp thẻ không được sau ngày hiện tại.',
            'ngay_het_han.required' => 'Ngày hết hạn là bắt buộc.',
            'ngay_het_han.after' => 'Ngày hết hạn phải sau ngày cấp thẻ.',
            'trang_thai.required' => 'Trạng thái là bắt buộc.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}























