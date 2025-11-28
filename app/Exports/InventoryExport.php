<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Inventory::with(['book', 'creator']);

        if ($this->request) {
            // Áp dụng các bộ lọc giống như controller
            if ($this->request->filled('book_id')) {
                $query->where('book_id', $this->request->book_id);
            }

            if ($this->request->filled('status')) {
                $query->where('status', $this->request->status);
            }

            if ($this->request->filled('condition')) {
                $query->where('condition', $this->request->condition);
            }

            if ($this->request->filled('location')) {
                $query->where('location', 'like', "%{$this->request->location}%");
            }

            if ($this->request->filled('barcode')) {
                $query->where('barcode', 'like', "%{$this->request->barcode}%");
            }

            if ($this->request->filled('book_title')) {
                $query->whereHas('book', function($bookQuery) {
                    $bookQuery->where('ten_sach', 'like', "%{$this->request->book_title}%");
                });
            }
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Mã vạch',
            'Tên sách',
            'Tác giả',
            'Vị trí',
            'Tình trạng',
            'Trạng thái',
            'Loại lưu trữ',
            'Giá mua',
            'Ngày mua',
            'Người tạo',
            'Ngày tạo',
        ];
    }

    public function map($inventory): array
    {
        return [
            $inventory->id,
            $inventory->barcode,
            $inventory->book->ten_sach ?? 'N/A',
            $inventory->book->tac_gia ?? 'N/A',
            $inventory->location,
            $inventory->condition,
            $inventory->status,
            $inventory->storage_type ?? 'Kho',
            $inventory->purchase_price ? number_format($inventory->purchase_price, 0, ',', '.') : '',
            $inventory->purchase_date ? $inventory->purchase_date->format('d/m/Y') : '',
            $inventory->creator->name ?? 'N/A',
            $inventory->created_at ? $inventory->created_at->format('d/m/Y H:i') : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 15,
            'C' => 30,
            'D' => 20,
            'E' => 20,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 20,
            'L' => 20,
        ];
    }
}
