<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\BorrowPayment;
use App\Services\VnPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VnPayController extends Controller
{
    protected $vnpayService;

    public function __construct(VnPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Tạo thanh toán cho phiếu mượn sách
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPayment(Request $request)
    {
        try {
            $request->validate([
                'borrow_id' => 'required|exists:borrows,id',
                'amount' => 'required|numeric|min:1000',
                'payment_type' => 'required|in:deposit,borrow_fee,shipping_fee,damage_fee',
                'bank_code' => 'nullable|string'
            ]);

            $borrowId = $request->borrow_id;
            $amount = $request->amount;
            $paymentType = $request->payment_type;

            // Lấy thông tin phiếu mượn
            $borrow = Borrow::with('reader')->findOrFail($borrowId);

            // Tạo thông tin thanh toán
            $paymentTypeText = [
                'deposit' => 'Tiền cọc',
                'borrow_fee' => 'Tiền thuê sách',
                'shipping_fee' => 'Phí vận chuyển',
                'damage_fee' => 'Phí đền bù'
            ];

            $orderInfo = sprintf(
                "%s - Phiếu mượn #%d - %s",
                $paymentTypeText[$paymentType],
                $borrowId,
                $borrow->reader->name ?? 'Khách hàng'
            );

            // Tạo mã giao dịch duy nhất
            $txnRef = 'BRW' . $borrowId . '_' . time();

            $paymentData = [
                'amount' => $amount,
                'order_info' => $orderInfo,
                'order_id' => $txnRef,
                'order_type' => 'billpayment', // Hoặc 'other'
                'bank_code' => $request->bank_code
            ];

            // Tạo bản ghi thanh toán với trạng thái pending
            $payment = BorrowPayment::create([
                'borrow_id' => $borrowId,
                'borrow_item_id' => $request->borrow_item_id ?? null,
                'amount' => $amount,
                'payment_type' => $paymentType,
                'payment_method' => 'online',
                'payment_status' => 'pending',
                'transaction_code' => $txnRef,
                'note' => 'Thanh toán qua VnPay'
            ]);

            // Lưu payment_id vào session để xử lý callback
            session(['vnpay_payment_id' => $payment->id]);

            // Tạo URL thanh toán
            $paymentUrl = $this->vnpayService->createPaymentUrl($paymentData, $request);

            return redirect($paymentUrl);

        } catch (\Exception $e) {
            Log::error('VnPay Create Payment Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý callback từ VnPay
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        try {
            // Xử lý response từ VnPay
            $result = $this->vnpayService->handleCallback($request);

            Log::info('VnPay Callback', [
                'result' => $result,
                'request_data' => $request->all()
            ]);

            if (!$result['success']) {
                return redirect()->route('payment.failed')
                    ->with('error', $result['message']);
            }

            // Lấy thông tin giao dịch
            $txnRef = $result['data']['order_id'];
            $vnpayTranId = $result['data']['transaction_id'];
            $amount = $result['data']['amount'];

            // Tìm bản ghi thanh toán
            $payment = BorrowPayment::where('transaction_code', $txnRef)
                ->where('payment_status', 'pending')
                ->first();

            if (!$payment) {
                Log::error('Payment not found', ['txnRef' => $txnRef]);
                return redirect()->route('payment.failed')
                    ->with('error', 'Không tìm thấy thông tin thanh toán');
            }

            // Bắt đầu transaction
            DB::beginTransaction();

            try {
                // Cập nhật trạng thái thanh toán
                $payment->update([
                    'payment_status' => 'success',
                    'transaction_code' => $vnpayTranId,
                    'note' => $payment->note . ' | VnPay TxnID: ' . $vnpayTranId . ' | Ngân hàng: ' . ($result['data']['bank_code'] ?? 'N/A')
                ]);

                // Cập nhật trạng thái phiếu mượn nếu là thanh toán cọc
                $borrow = $payment->borrow;
                if ($payment->payment_type === 'deposit' && $borrow) {
                    // Có thể cập nhật trạng thái phiếu mượn ở đây nếu cần
                    // Ví dụ: chuyển từ 'cho_xu_ly' sang 'da_thu_coc'
                }

                DB::commit();

                // Xóa session
                session()->forget('vnpay_payment_id');

                return redirect()->route('payment.success', ['payment_id' => $payment->id])
                    ->with('success', 'Thanh toán thành công!');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('VnPay Callback Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('payment.failed')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
        }
    }

    /**
     * Trang thanh toán thành công
     */
    public function success($payment_id)
    {
        $payment = BorrowPayment::with(['borrow.reader', 'borrowItem'])->findOrFail($payment_id);

        return view('payments.success', compact('payment'));
    }

    /**
     * Trang thanh toán thất bại
     */
    public function failed()
    {
        return view('payments.failed');
    }

    /**
     * API: Tạo thanh toán cho giỏ mượn sách
     * Dùng cho trường hợp thanh toán từ giỏ hàng
     */
    public function createPaymentFromCart(Request $request)
    {
        try {
            $request->validate([
                'borrow_cart_id' => 'required|exists:borrow_carts,id',
                'amount' => 'required|numeric|min:1000',
                'bank_code' => 'nullable|string'
            ]);

            $cartId = $request->borrow_cart_id;
            $amount = $request->amount;

            // Lấy thông tin giỏ mượn
            $cart = \App\Models\BorrowCart::with('reader')->findOrFail($cartId);

            $orderInfo = sprintf(
                "Thanh toán giỏ mượn #%d - %s",
                $cartId,
                $cart->reader->name ?? 'Khách hàng'
            );

            // Tạo mã giao dịch
            $txnRef = 'CART' . $cartId . '_' . time();

            $paymentData = [
                'amount' => $amount,
                'order_info' => $orderInfo,
                'order_id' => $txnRef,
                'order_type' => 'billpayment',
                'bank_code' => $request->bank_code
            ];

            // Lưu thông tin vào session
            session([
                'vnpay_cart_id' => $cartId,
                'vnpay_txn_ref' => $txnRef
            ]);

            // Tạo URL thanh toán
            $paymentUrl = $this->vnpayService->createPaymentUrl($paymentData, $request);

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl
            ]);

        } catch (\Exception $e) {
            Log::error('VnPay Create Cart Payment Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}

