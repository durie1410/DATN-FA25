<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Tạo thanh toán VNPay
     */
    public function createVNPayPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        
        $order = Order::findOrFail($orderId);
        
        // Kiểm tra quyền sở hữu đơn hàng
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Không có quyền truy cập đơn hàng này');
        }
        
        // Kiểm tra trạng thái đơn hàng
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id)
                ->with('info', 'Đơn hàng này đã được thanh toán');
        }
        
        $orderInfo = "Thanh toán đơn hàng #{$order->order_number}";
        $amount = $order->total_amount;
        
        $paymentUrl = $this->vnpayService->createPaymentUrl(
            $order->id,
            $amount,
            $orderInfo,
            $request->ip()
        );
        
        return redirect($paymentUrl);
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function vnpayReturn(Request $request)
    {
        $inputData = $request->all();
        
        Log::info('VNPay Return Callback', $inputData);
        
        // Xác thực chữ ký
        if (!$this->vnpayService->verifyReturnUrl($inputData)) {
            return redirect()->route('orders.index')
                ->with('error', 'Chữ ký không hợp lệ');
        }
        
        $transactionInfo = $this->vnpayService->getTransactionInfo($inputData);
        $responseCode = $transactionInfo['response_code'];
        
        // Lấy order_id từ vnp_TxnRef (format: {order_id}_{timestamp})
        $txnRef = $transactionInfo['txn_ref'];
        $orderId = explode('_', $txnRef)[0];
        
        $order = Order::find($orderId);
        
        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Không tìm thấy đơn hàng');
        }
        
        DB::beginTransaction();
        
        try {
            if ($this->vnpayService->isSuccess($responseCode)) {
                // Thanh toán thành công
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'vnpay',
                    'transaction_id' => $transactionInfo['transaction_no'],
                    'paid_at' => now(),
                ]);
                
                Log::info('VNPay Payment Success', [
                    'order_id' => $order->id,
                    'transaction_no' => $transactionInfo['transaction_no']
                ]);
                
                DB::commit();
                
                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Thanh toán thành công! Mã giao dịch: ' . $transactionInfo['transaction_no']);
            } else {
                // Thanh toán thất bại
                $message = $this->vnpayService->getResponseMessage($responseCode);
                
                Log::warning('VNPay Payment Failed', [
                    'order_id' => $order->id,
                    'response_code' => $responseCode,
                    'message' => $message
                ]);
                
                DB::commit();
                
                return redirect()->route('orders.show', $order->id)
                    ->with('error', 'Thanh toán thất bại: ' . $message);
            }
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('VNPay Payment Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('orders.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
        }
    }

    /**
     * Tạo thanh toán MoMo (placeholder)
     */
    public function createMoMoPayment(Request $request)
    {
        // TODO: Implement MoMo payment
        return redirect()->back()
            ->with('info', 'Chức năng thanh toán MoMo đang được phát triển');
    }
}

