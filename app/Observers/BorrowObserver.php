<?php

namespace App\Observers;

use App\Models\Borrow;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\BorrowPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BorrowObserver
{
    /**
     * Handle the Borrow "updated" event.
     * Tự động hoàn tiền khi đơn hàng bị hủy hoặc hoàn tất
     */
    public function updated(Borrow $borrow)
    {
        // Xử lý khi trạng thái vừa đổi sang "Huy"
        if ($borrow->isDirty('trang_thai') && $borrow->trang_thai === 'Huy') {
            // Kiểm tra nhanh trước khi xử lý
            if (!$borrow->reader_id) {
                return;
            }
            
            // Chạy sau khi transaction commit để tránh lock timeout
            // Sử dụng DB::afterCommit() để đảm bảo chạy sau khi transaction hoàn tất
            if (DB::transactionLevel() > 0) {
                DB::afterCommit(function () use ($borrow) {
                    // Reload borrow để đảm bảo có dữ liệu mới nhất
                    $borrow->refresh();
                    $this->processRefundForCancelledOrder($borrow);
                });
            } else {
                // Nếu không có transaction, chạy trực tiếp
                $this->processRefundForCancelledOrder($borrow);
            }
        }
        
        // Xử lý khi trạng thái chi tiết vừa đổi sang "hoan_tat_don_hang"
        if ($borrow->isDirty('trang_thai_chi_tiet') && $borrow->trang_thai_chi_tiet === Borrow::STATUS_HOAN_TAT_DON_HANG) {
            // Kiểm tra nhanh trước khi xử lý
            if (!$borrow->reader_id) {
                return;
            }
            
            // Lưu borrow ID để reload sau
            $borrowId = $borrow->id;
            
            // Chạy sau khi transaction commit để tránh lock timeout và đảm bảo transaction hoàn tiền trong completeOrder() đã commit
            if (DB::transactionLevel() > 0) {
                DB::afterCommit(function () use ($borrowId) {
                    // Reload borrow từ database để đảm bảo có dữ liệu mới nhất
                    $borrow = Borrow::with(['reader'])->find($borrowId);
                    if ($borrow) {
                        $this->processRefundForCompletedOrder($borrow);
                    }
                });
            } else {
                // Nếu không có transaction, chạy trực tiếp
                $this->processRefundForCompletedOrder($borrow);
            }
        }
    }

    /**
     * Xử lý hoàn tiền cho đơn đã hủy
     */
    protected function processRefundForCancelledOrder(Borrow $borrow)
    {
        try {
            // Kiểm tra đã có transaction hoàn tiền chưa (tránh hoàn trùng) - query nhanh
            $existingTransaction = WalletTransaction::where('reference_type', Borrow::class)
                ->where('reference_id', $borrow->id)
                ->where('type', 'refund')
                ->exists(); // Dùng exists() thay vì first() để nhanh hơn

            if ($existingTransaction) {
                return; // Bỏ qua log để nhanh hơn
            }

            // Load reader nếu chưa có (chỉ load user_id)
            if (!$borrow->relationLoaded('reader')) {
                $borrow->load('reader:id,user_id');
            }
            
            // Kiểm tra user_id ngay
            if (!$borrow->reader || !$borrow->reader->user_id) {
                return;
            }

            // Kiểm tra payment method trước (chỉ cần exists, không cần load full)
            $hasOnlinePayment = DB::table('borrow_payments')
                ->where('borrow_id', $borrow->id)
                ->where('payment_method', 'online')
                ->where('payment_status', 'success')
                ->exists();

            // Nếu không có online payment, bỏ qua ngay (COD không cần hoàn)
            if (!$hasOnlinePayment) {
                return;
            }

            // Chỉ tính lại tổng tiền nếu cần
            if ($borrow->tien_coc <= 0) {
                $borrow->recalculateTotals();
                $borrow->refresh();
            }

            if ($borrow->tien_coc <= 0) {
                return; // Bỏ qua log để nhanh hơn
            }

            // Tính số tiền cần hoàn (đã kiểm tra online payment ở trên)
            $refundAmount = $borrow->tien_coc + $borrow->tien_thue + ($borrow->tien_ship ?? 0);
            
            if ($refundAmount <= 0) {
                return;
            }

            // Tạo mô tả hoàn tiền
            $refundDescription = 'Hoàn tiền cho đơn mượn #' . $borrow->id . ' (Đơn hàng đã hủy - thanh toán online)';
            
            $details = [];
            if ($borrow->tien_coc > 0) {
                $details[] = 'Tiền cọc: ' . number_format($borrow->tien_coc, 0, ',', '.') . ' VNĐ';
            }
            if ($borrow->tien_thue > 0) {
                $details[] = 'Phí thuê: ' . number_format($borrow->tien_thue, 0, ',', '.') . ' VNĐ';
            }
            if (($borrow->tien_ship ?? 0) > 0) {
                $details[] = 'Phí ship: ' . number_format($borrow->tien_ship, 0, ',', '.') . ' VNĐ';
            }
            
            if (!empty($details)) {
                $refundDescription .= ' - ' . implode(', ', $details);
            }

            // Thực hiện hoàn tiền
            DB::beginTransaction();

            $wallet = Wallet::getOrCreateForUser($borrow->reader->user_id);
            $balanceBefore = $wallet->balance;

            $wallet->refund(
                $refundAmount,
                $refundDescription,
                $borrow
            );

            $wallet->refresh();
            $balanceAfter = $wallet->balance;

            DB::commit();

            Log::info('Auto-refunded cancelled order', [
                'borrow_id' => $borrow->id,
                'user_id' => $borrow->reader->user_id,
                'amount' => $refundAmount,
                'wallet_id' => $wallet->id,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'payment_method' => 'online',
                'tien_coc' => $borrow->tien_coc,
                'tien_thue' => $borrow->tien_thue,
                'tien_ship' => $borrow->tien_ship ?? 0,
                'total_refund' => $refundAmount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error auto-refunding cancelled order', [
                'borrow_id' => $borrow->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Xử lý hoàn tiền cọc cho đơn đã hoàn tất
     */
    protected function processRefundForCompletedOrder(Borrow $borrow)
    {
        try {
            // Sử dụng database lock để tránh race condition và đảm bảo chỉ hoàn tiền 1 lần
            DB::transaction(function () use ($borrow) {
                // Kiểm tra đã có transaction hoàn tiền chưa (tránh hoàn trùng)
                // Sử dụng lockForUpdate để đảm bảo chỉ một process có thể tạo transaction
                $existingTransaction = WalletTransaction::where('reference_type', Borrow::class)
                    ->where('reference_id', $borrow->id)
                    ->where('type', 'refund')
                    ->lockForUpdate()
                    ->first();

                if ($existingTransaction) {
                    return; // Đã hoàn tiền rồi, bỏ qua
                }

                // Reload borrow để đảm bảo có dữ liệu mới nhất
                $borrow->refresh();
                
                // Load reader nếu chưa có
                if (!$borrow->relationLoaded('reader')) {
                    $borrow->load('reader:id,user_id');
                }
                
                // Kiểm tra user_id
                if (!$borrow->reader || !$borrow->reader->user_id) {
                    Log::warning('Cannot refund completed order - missing reader or user_id', [
                        'borrow_id' => $borrow->id,
                        'has_reader' => $borrow->reader ? true : false,
                        'reader_user_id' => $borrow->reader ? $borrow->reader->user_id : null
                    ]);
                    return;
                }

                // Đảm bảo tien_coc được tính lại từ items (nếu chưa đúng)
                if ($borrow->tien_coc <= 0) {
                    $borrow->recalculateTotals();
                    $borrow->refresh();
                }

                // Kiểm tra các điều kiện cần thiết để hoàn tiền
                if ($borrow->tien_coc <= 0) {
                    Log::info('Skipped refund - no deposit', [
                        'borrow_id' => $borrow->id,
                        'tien_coc' => $borrow->tien_coc
                    ]);
                    return;
                }

                // Kiểm tra nếu khách từ chối nhận hàng và đã thanh toán online
                $isRejectedDelivery = $borrow->customer_rejected_delivery ?? false;
                $hasOnlinePayment = BorrowPayment::where('borrow_id', $borrow->id)
                    ->where('payment_method', 'online')
                    ->where('payment_status', 'success')
                    ->exists();

                $refundAmount = 0;
                $description = '';

                if ($isRejectedDelivery && $hasOnlinePayment) {
                    // Trường hợp từ chối nhận hàng và đã thanh toán online: chỉ hoàn tiền cọc và tiền thuê (KHÔNG hoàn tiền ship)
                    $refundAmount = $borrow->tien_coc + $borrow->tien_thue;
                    $description = 'Hoàn tiền cọc và tiền thuê cho đơn mượn #' . $borrow->id . ' (Khách hàng từ chối nhận sách - thanh toán online, không hoàn tiền ship)';
                } else {
                    // Trường hợp bình thường: chỉ hoàn tiền cọc (đã trừ phí hỏng sách nếu có)
                    $borrow->updateRefundAmount();
                    $borrow->refresh();

                    if ($borrow->tien_coc_hoan_tra <= 0) {
                        Log::info('Skipped refund - refund amount <= 0', [
                            'borrow_id' => $borrow->id,
                            'tien_coc' => $borrow->tien_coc,
                            'phi_hong_sach' => $borrow->phi_hong_sach,
                            'tien_coc_hoan_tra' => $borrow->tien_coc_hoan_tra
                        ]);
                        return;
                    }

                    $refundAmount = $borrow->tien_coc_hoan_tra;
                    $description = 'Hoàn tiền cọc cho đơn mượn #' . $borrow->id;
                    if ($borrow->phi_hong_sach > 0) {
                        $description .= ' (Đã trừ phí hỏng sách: ' . number_format($borrow->phi_hong_sach, 0, ',', '.') . ' VNĐ)';
                    }
                }

                if ($refundAmount <= 0) {
                    Log::info('Skipped refund - refund amount <= 0', [
                        'borrow_id' => $borrow->id,
                        'refund_amount' => $refundAmount
                    ]);
                    return;
                }

                // Thực hiện hoàn tiền
                $wallet = Wallet::getOrCreateForUser($borrow->reader->user_id);
                $balanceBefore = $wallet->balance;

                $wallet->refund(
                    $refundAmount,
                    $description,
                    $borrow
                );

                $wallet->refresh();
                $balanceAfter = $wallet->balance;

                Log::info('Auto-refunded completed order', [
                    'borrow_id' => $borrow->id,
                    'user_id' => $borrow->reader->user_id,
                    'is_rejected_delivery' => $isRejectedDelivery ?? false,
                    'has_online_payment' => $hasOnlinePayment ?? false,
                    'tien_coc' => $borrow->tien_coc,
                    'tien_thue' => $borrow->tien_thue,
                    'tien_ship' => $borrow->tien_ship ?? 0,
                    'phi_hong_sach' => $borrow->phi_hong_sach ?? 0,
                    'tien_coc_hoan_tra' => $borrow->tien_coc_hoan_tra ?? 0,
                    'refund_amount' => $refundAmount,
                    'wallet_id' => $wallet->id,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter
                ]);
            });

        } catch (\Exception $e) {
            Log::error('Error auto-refunding completed order', [
                'borrow_id' => $borrow->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

