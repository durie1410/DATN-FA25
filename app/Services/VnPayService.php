<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VnPayService
{
    private $vnpayConfig;

    public function __construct()
    {
        $this->vnpayConfig = Config::get('services.vnpay');
    }

    /**
     * Tạo URL thanh toán VnPay
     * 
     * @param array $paymentData - Thông tin thanh toán
     *   - amount: Số tiền thanh toán
     *   - order_info: Thông tin đơn hàng
     *   - order_id: Mã đơn hàng (tùy chọn, nếu không có sẽ tạo tự động)
     *   - order_type: Loại đơn hàng (mặc định: 'other')
     * @param Request $request
     * @return string URL thanh toán
     */
    public function createPaymentUrl(array $paymentData, Request $request)
    {
        // Lấy thời gian hiện tại theo timezone
        $timeZone = Config::get('app.timezone', 'Asia/Ho_Chi_Minh');
        $timeNow = Carbon::now($timeZone);
        
        // Tạo mã giao dịch (txnRef) nếu chưa có
        $txnRef = $paymentData['order_id'] ?? $timeNow->timestamp . rand(1000, 9999);
        
        // URL callback
        $returnUrl = $this->vnpayConfig['return_url'] ?? route('vnpay.callback');
        
        // Khởi tạo VnPayLibrary
        $vnpay = new VnPayLibrary();
        
        // Thêm các tham số bắt buộc
        $vnpay->addRequestData('vnp_Version', $this->vnpayConfig['version']);
        $vnpay->addRequestData('vnp_Command', $this->vnpayConfig['command']);
        $vnpay->addRequestData('vnp_TmnCode', $this->vnpayConfig['tmn_code']);
        $vnpay->addRequestData('vnp_Amount', (int)($paymentData['amount'] * 100)); // VnPay yêu cầu số tiền nhân với 100
        $vnpay->addRequestData('vnp_CreateDate', $timeNow->format('YmdHis'));
        $vnpay->addRequestData('vnp_CurrCode', $this->vnpayConfig['curr_code']);
        $vnpay->addRequestData('vnp_IpAddr', $vnpay->getIpAddress($request));
        $vnpay->addRequestData('vnp_Locale', $this->vnpayConfig['locale']);
        $vnpay->addRequestData('vnp_OrderInfo', $paymentData['order_info']);
        $vnpay->addRequestData('vnp_OrderType', $paymentData['order_type'] ?? 'other');
        $vnpay->addRequestData('vnp_ReturnUrl', $returnUrl);
        $vnpay->addRequestData('vnp_TxnRef', $txnRef);
        
        // Thêm thông tin ngân hàng nếu có
        if (!empty($paymentData['bank_code'])) {
            $vnpay->addRequestData('vnp_BankCode', $paymentData['bank_code']);
        }
        
        // Tạo URL thanh toán
        $paymentUrl = $vnpay->createRequestUrl(
            $this->vnpayConfig['url'],
            $this->vnpayConfig['hash_secret']
        );
        
        return $paymentUrl;
    }

    /**
     * Xử lý callback từ VnPay sau khi thanh toán
     * 
     * @param Request $request
     * @return array Kết quả thanh toán
     */
    public function handleCallback(Request $request)
    {
        Log::info('VNPay Callback Received', [
            'all_params' => $request->all(),
            'hash_secret_configured' => !empty($this->vnpayConfig['hash_secret']),
            'hash_secret_length' => strlen($this->vnpayConfig['hash_secret'] ?? ''),
        ]);
        
        $vnpay = new VnPayLibrary();
        $response = $vnpay->getFullResponseData($request, $this->vnpayConfig['hash_secret']);
        
        // Kiểm tra chữ ký
        if (!$response['success']) {
            Log::error('VNPay Signature Validation Failed', [
                'message' => $response['message'],
                'hash_secret_length' => strlen($this->vnpayConfig['hash_secret'] ?? ''),
                'suggestion' => 'Kiểm tra VNPAY_HASH_SECRET trong file .env có khớp với VNPay không'
            ]);
            
            return [
                'success' => false,
                'message' => 'Xác thực chữ ký thất bại',
                'data' => null
            ];
        }
        
        // Kiểm tra response code từ VnPay
        $vnpayResponseCode = $response['vnpay_response_code'];
        
        if ($vnpayResponseCode === '00') {
            // Giao dịch thành công
            return [
                'success' => true,
                'message' => 'Thanh toán thành công',
                'data' => $response
            ];
        } else {
            // Giao dịch thất bại
            return [
                'success' => false,
                'message' => $this->getResponseMessage($vnpayResponseCode),
                'data' => $response
            ];
        }
    }

    /**
     * Lấy thông điệp lỗi từ response code
     */
    private function getResponseMessage($responseCode)
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)'
        ];
        
        return $messages[$responseCode] ?? 'Lỗi không xác định';
    }

    /**
     * Kiểm tra trạng thái giao dịch với VnPay (Query API)
     * Chức năng này dùng để tra cứu thông tin giao dịch
     */
    public function queryTransaction($txnRef, $transDate)
    {
        // Implement nếu cần tra cứu giao dịch
        // Tham khảo API VnPay Query
    }
}

