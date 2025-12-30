<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    /**
     * Tính phí vận chuyển từ địa chỉ khách hàng
     */
    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:500',
        ], [
            'address.required' => 'Vui lòng nhập địa chỉ',
            'address.string' => 'Địa chỉ không hợp lệ',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $shippingService = new ShippingService();
            $result = $shippingService->calculateShipping($request->address);

            // Nếu không thành công nhưng có message (ví dụ: không có API key), vẫn trả về success với phí = 0
            if (!$result['success'] && isset($result['message'])) {
                return response()->json([
                    'success' => true,
                    'distance' => 0,
                    'shipping_fee' => 0,
                    'duration' => 0,
                    'message' => $result['message'] ?? 'Phí vận chuyển = 0₫ (chưa cấu hình API key)'
                ]);
            }

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Không thể tính phí vận chuyển',
                    'distance' => 0,
                    'shipping_fee' => 0
                ], 400);
            }

            return response()->json([
                'success' => true,
                'distance' => $result['distance'],
                'shipping_fee' => $result['shipping_fee'],
                'duration' => $result['duration'] ?? 0,
                'message' => $result['message'] ?? 'Tính phí vận chuyển thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('ShippingController calculate exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'address' => $request->address
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính phí vận chuyển',
                'distance' => 0,
                'shipping_fee' => 0
            ], 500);
        }
    }
}

