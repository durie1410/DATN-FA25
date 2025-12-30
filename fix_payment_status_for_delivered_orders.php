<?php

/**
 * Script để fix trạng thái thanh toán cho các đơn hàng đã giao hàng thành công
 * Chạy: php fix_payment_status_for_delivered_orders.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Borrow;
use Illuminate\Support\Facades\DB;

fwrite(STDOUT, "Bắt đầu fix trạng thái thanh toán cho các đơn hàng đã giao hàng thành công...\n\n");

// Tìm tất cả các đơn hàng đã giao hàng thành công hoặc hoàn tất nhưng chưa cập nhật thanh toán
$borrows = Borrow::whereIn('trang_thai_chi_tiet', [
    'giao_hang_thanh_cong',
    'da_muon_dang_luu_hanh',
    'hoan_tat_don_hang'
])
->whereHas('payments', function($query) {
    $query->where('payment_status', 'pending')
          ->whereIn('payment_type', ['deposit', 'borrow_fee', 'shipping_fee']);
})
->with('payments')
->get();

fwrite(STDOUT, "Tìm thấy " . $borrows->count() . " đơn hàng cần fix.\n\n");

$count = 0;
$totalUpdated = 0;

foreach ($borrows as $borrow) {
    $pendingPayments = $borrow->payments()
        ->where('payment_status', 'pending')
        ->whereIn('payment_type', ['deposit', 'borrow_fee', 'shipping_fee'])
        ->get();
    
    if ($pendingPayments->count() > 0) {
        $count++;
        $updated = $borrow->payments()
            ->where('payment_status', 'pending')
            ->whereIn('payment_type', ['deposit', 'borrow_fee', 'shipping_fee'])
            ->update([
                'payment_status' => 'success',
                'note' => DB::raw("CONCAT(COALESCE(note, ''), ' - Đã tự động cập nhật thanh toán do đơn hàng đã giao thành công (fix script)')"),
                'updated_at' => now()
            ]);
        
        $totalUpdated += $updated;
        
        fwrite(STDOUT, "✓ Đơn hàng #{$borrow->id} ({$borrow->trang_thai_chi_tiet}): Đã cập nhật {$updated} khoản thanh toán\n");
    }
}

fwrite(STDOUT, "\n");
fwrite(STDOUT, "Hoàn tất! Đã fix {$count} đơn hàng, tổng cộng {$totalUpdated} khoản thanh toán được cập nhật.\n");
