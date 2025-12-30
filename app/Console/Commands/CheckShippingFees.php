<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrow;

class CheckShippingFees extends Command
{
    protected $signature = 'borrows:check-shipping-fees {--limit=10 : Số lượng phiếu mượn để kiểm tra}';
    protected $description = 'Kiểm tra dữ liệu tien_ship trong database';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        
        $this->info("🔍 Kiểm tra {$limit} phiếu mượn đầu tiên:");
        $this->newLine();
        
        $borrows = Borrow::with('items')->take($limit)->get();
        
        $data = [];
        foreach ($borrows as $borrow) {
            $tienShipFromBorrow = floatval($borrow->tien_ship ?? 0);
            $tienShipFromItems = $borrow->items ? $borrow->items->sum('tien_ship') : 0;
            $itemsCount = $borrow->items ? $borrow->items->count() : 0;
            
            $data[] = [
                'ID' => $borrow->id,
                'tien_ship (borrows)' => number_format($tienShipFromBorrow, 0, ',', '.') . '₫',
                'tien_ship (items sum)' => number_format($tienShipFromItems, 0, ',', '.') . '₫',
                'Items count' => $itemsCount,
                'Status' => $tienShipFromBorrow == $tienShipFromItems ? '✅ OK' : '⚠️ Khác nhau',
            ];
        }
        
        $this->table(
            ['ID', 'tien_ship (borrows)', 'tien_ship (items sum)', 'Items count', 'Status'],
            $data
        );
        
        // Thống kê
        $total = $borrows->count();
        $ok = collect($data)->where('Status', '✅ OK')->count();
        $different = collect($data)->where('Status', '⚠️ Khác nhau')->count();
        
        $this->newLine();
        $this->info("📊 Thống kê:");
        $this->line("   Tổng số: {$total}");
        $this->line("   Đồng bộ: {$ok}");
        $this->line("   Khác nhau: {$different}");
        
        return 0;
    }
}


