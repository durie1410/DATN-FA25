<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowRequest extends FormRequest
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
        return [
            'reader_id' => 'required|exists:readers,id',
            'book_id' => 'required|exists:books,id',
            'ngay_muon' => 'required|date|before_or_equal:today',
            'ngay_hen_tra' => 'required|date|after:ngay_muon',
            'ghi_chu' => 'nullable|string|max:500',
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
            'reader_id.required' => 'Độc giả là bắt buộc.',
            'reader_id.exists' => 'Độc giả không tồn tại.',
            'book_id.required' => 'Sách là bắt buộc.',
            'book_id.exists' => 'Sách không tồn tại.',
            'ngay_muon.required' => 'Ngày mượn là bắt buộc.',
            'ngay_muon.date' => 'Ngày mượn không đúng định dạng.',
            'ngay_muon.before_or_equal' => 'Ngày mượn không được sau ngày hiện tại.',
            'ngay_hen_tra.required' => 'Ngày hẹn trả là bắt buộc.',
            'ngay_hen_tra.date' => 'Ngày hẹn trả không đúng định dạng.',
            'ngay_hen_tra.after' => 'Ngày hẹn trả phải sau ngày mượn.',
            'ghi_chu.max' => 'Ghi chú không được vượt quá 500 ký tự.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Kiểm tra sách có sẵn không
            if ($this->book_id) {
                $book = \App\Models\Book::find($this->book_id);
                if ($book && !$book->isAvailable()) {
                    $validator->errors()->add('book_id', 'Sách này hiện không có sẵn để mượn.');
                }
            }

            // Kiểm tra độc giả có thể mượn sách không
            if ($this->reader_id) {
                $reader = \App\Models\Reader::find($this->reader_id);
                if ($reader && $reader->trang_thai !== 'Hoat dong') {
                    $validator->errors()->add('reader_id', 'Độc giả này không thể mượn sách.');
                }
            }
        });
    }
}























