<?php

namespace App\Exports;

use App\Models\ReportTemplate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $template;
    protected $data;

    public function __construct(ReportTemplate $template, $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        $headings = [];
        foreach ($this->template->columns as $column) {
            $headings[] = $this->getColumnLabel($column);
        }
        return $headings;
    }

    public function map($item): array
    {
        $row = [];
        foreach ($this->template->columns as $column) {
            $row[] = $this->getColumnValue($item, $column);
        }
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        $widths = [];
        foreach ($this->template->columns as $column) {
            $widths[] = 15; // Default width
        }
        return $widths;
    }

    private function getColumnLabel($column)
    {
        $labels = [
            'id' => 'ID',
            'ten_sach' => 'Tên sách',
            'tac_gia' => 'Tác giả',
            'nam_xuat_ban' => 'Năm xuất bản',
            'ten_the_loai' => 'Thể loại',
            'ho_ten' => 'Họ tên',
            'email' => 'Email',
            'so_dien_thoai' => 'Số điện thoại',
            'ngay_muon' => 'Ngày mượn',
            'ngay_hen_tra' => 'Ngày hẹn trả',
            'ngay_tra_thuc_te' => 'Ngày trả thực tế',
            'trang_thai' => 'Trạng thái',
            'amount' => 'Số tiền',
            'type' => 'Loại',
            'due_date' => 'Hạn thanh toán',
            'reservation_date' => 'Ngày đặt trước',
            'expiry_date' => 'Ngày hết hạn',
            'priority' => 'Độ ưu tiên',
        ];

        return $labels[$column] ?? ucfirst(str_replace('_', ' ', $column));
    }

    private function getColumnValue($item, $column)
    {
        if (is_array($item)) {
            return $item[$column] ?? '';
        }

        // Handle nested relationships
        if (strpos($column, '.') !== false) {
            $parts = explode('.', $column);
            $value = $item;
            foreach ($parts as $part) {
                if (isset($value->$part)) {
                    $value = $value->$part;
                } else {
                    return '';
                }
            }
            return $value;
        }

        return $item->$column ?? '';
    }
}



