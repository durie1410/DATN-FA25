<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Category::withCount('books');

        if ($this->request) {
            // Áp dụng các bộ lọc giống như controller
            if ($this->request->filled('keyword')) {
                $keyword = $this->request->keyword;
                $query->where('ten_the_loai', 'like', "%{$keyword}%");
            }

            if ($this->request->filled('min_books')) {
                $query->having('books_count', '>=', $this->request->min_books);
            }
            if ($this->request->filled('max_books')) {
                $query->having('books_count', '<=', $this->request->max_books);
            }
        }

        return $query->orderBy('ten_the_loai')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên thể loại',
            'Mô tả',
            'Trạng thái',
            'Màu sắc',
            'Icon',
            'Số sách',
            'Ngày tạo',
            'Ngày cập nhật',
        ];
    }

    public function map($category): array
    {
        return [
            $category->id,
            $category->ten_the_loai,
            $category->mo_ta ?? '',
            $category->trang_thai == 'active' ? 'Hoạt động' : 'Không hoạt động',
            $category->mau_sac ?? '',
            $category->icon ?? '',
            $category->books_count,
            $category->created_at->format('d/m/Y H:i'),
            $category->updated_at->format('d/m/Y H:i'),
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
            'A' => 8,   // ID
            'B' => 25,  // Tên thể loại
            'C' => 40,  // Mô tả
            'D' => 15,  // Trạng thái
            'E' => 12,  // Màu sắc
            'F' => 15,  // Icon
            'G' => 10,  // Số sách
            'H' => 18,  // Ngày tạo
            'I' => 18,  // Ngày cập nhật
        ];
    }
}

