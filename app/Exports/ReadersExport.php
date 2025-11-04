<?php

namespace App\Exports;

use App\Models\Reader;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReadersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Reader::query();

        // Áp dụng các bộ lọc giống như controller
        if ($this->request->filled('keyword')) {
            $keyword = $this->request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('so_the_doc_gia', 'like', "%{$keyword}%");
            });
        }

        if ($this->request->filled('trang_thai')) {
            $query->where('trang_thai', $this->request->trang_thai);
        }

        if ($this->request->filled('gioi_tinh')) {
            $query->where('gioi_tinh', $this->request->gioi_tinh);
        }

        return $query->orderBy('ho_ten');
    }

    public function headings(): array
    {
        return [
            'Mã Độc Giả',
            'Họ Tên',
            'Email',
            'Số Điện Thoại',
            'Ngày Sinh',
            'Giới Tính',
            'Địa Chỉ',
            'Ngày Cấp Thẻ',
            'Ngày Hết Hạn',
            'Trạng Thái',
            'Ngày Tạo',
        ];
    }

    public function map($reader): array
    {
        return [
            $reader->so_the_doc_gia,
            $reader->ho_ten,
            $reader->email,
            $reader->so_dien_thoai,
            $reader->ngay_sinh ? $reader->ngay_sinh->format('d/m/Y') : '',
            $reader->gioi_tinh,
            $reader->dia_chi,
            $reader->ngay_cap_the ? $reader->ngay_cap_the->format('d/m/Y') : '',
            $reader->ngay_het_han ? $reader->ngay_het_han->format('d/m/Y') : '',
            $reader->trang_thai,
            $reader->created_at ? $reader->created_at->format('d/m/Y H:i') : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Mã Độc Giả
            'B' => 25, // Họ Tên
            'C' => 30, // Email
            'D' => 15, // Số Điện Thoại
            'E' => 12, // Ngày Sinh
            'F' => 10, // Giới Tính
            'G' => 40, // Địa Chỉ
            'H' => 15, // Ngày Cấp Thẻ
            'I' => 15, // Ngày Hết Hạn
            'J' => 15, // Trạng Thái
            'K' => 20, // Ngày Tạo
        ];
    }
}

