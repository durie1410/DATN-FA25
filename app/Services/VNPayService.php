<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VNPayService
{
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_Url;
    private $vnp_ReturnUrl;

    public function __construct()
    {
        $this->vnp_TmnCode = env('VNPAY_TMN_CODE', 'DEMO');
        $this->vnp_HashSecret = env('VNPAY_HASH_SECRET', 'DEMO');
        $this->vnp_Url = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->vnp_ReturnUrl = route('payment.vnpay.return');
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($orderId, $amount, $orderInfo, $ipAddr = null)
    {
        $vnp_TxnRef = $orderId . '_' . time();
        $vnp_OrderInfo = $orderInfo;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền nhân 100
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $ipAddr ?? request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $this->vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (strlen($vnp_BankCode) > 0) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_Url . "?" . $query;
        if (isset($this->vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        Log::info('VNPay Payment URL Created', [
            'order_id' => $orderId,
            'amount' => $amount,
            'txn_ref' => $vnp_TxnRef
        ]);

        return $vnp_Url;
    }

    /**
     * Xác thực callback từ VNPay
     */
    public function verifyReturnUrl($inputData)
    {
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);

        Log::info('VNPay Return Verification', [
            'secure_hash' => $vnp_SecureHash,
            'calculated_hash' => $secureHash,
            'match' => $secureHash === $vnp_SecureHash
        ]);

        return $secureHash === $vnp_SecureHash;
    }

    /**
     * Lấy thông tin giao dịch từ response
     */
    public function getTransactionInfo($inputData)
    {
        return [
            'txn_ref' => $inputData['vnp_TxnRef'] ?? null,
            'amount' => isset($inputData['vnp_Amount']) ? $inputData['vnp_Amount'] / 100 : 0,
            'order_info' => $inputData['vnp_OrderInfo'] ?? '',
            'response_code' => $inputData['vnp_ResponseCode'] ?? '',
            'transaction_no' => $inputData['vnp_TransactionNo'] ?? '',
            'bank_code' => $inputData['vnp_BankCode'] ?? '',
            'pay_date' => $inputData['vnp_PayDate'] ?? '',
            'card_type' => $inputData['vnp_CardType'] ?? '',
        ];
    }

    /**
     * Kiểm tra giao dịch thành công
     */
    public function isSuccess($responseCode)
    {
        return $responseCode === '00';
    }

    /**
     * Lấy message từ response code
     */
    public function getResponseMessage($responseCode)
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP).',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định.',
            '99' => 'Các lỗi khác',
        ];

        return $messages[$responseCode] ?? 'Lỗi không xác định';
    }
}

