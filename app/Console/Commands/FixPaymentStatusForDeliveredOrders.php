<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPaymentStatusForDeliveredOrders extends Command
{
    protected $signature = 'fix:payment-status-delivered';
    protected $description = 'Fix payment status for orders that are delivered but still show unpaid';

    public function handle()
    {
        $this->info('Bắt đầu fix trạng thái thanh toán cho các đơn hàng đã giao hàng thành công...');
        $this->newLine();

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

        $this->info("Tìm thấy {$borrows->count()} đơn hàng cần fix.");
        $this->newLine();

        if ($borrows->count() === 0) {
            $this->info('Không có đơn hàng nào cần fix.');
            return 0;
        }

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
                        'note' => DB::raw("CONCAT(COALESCE(note, ''), ' - Đã tự động cập nhật thanh toán do đơn hàng đã giao thành công (fix command)')"),
                        'updated_at' => now()
                    ]);
                
                $totalUpdated += $updated;
                
                $this->line("✓ Đơn hàng #{$borrow->id} ({$borrow->trang_thai_chi_tiet}): Đã cập nhật {$updated} khoản thanh toán");
            }
        }

        $this->newLine();
        $this->info("Hoàn tất! Đã fix {$count} đơn hàng, tổng cộng {$totalUpdated} khoản thanh toán được cập nhật.");

        return 0;
    }
}
