<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ShippingService
{
    /**
     * Địa chỉ thư viện mặc định (có thể cấu hình trong .env)
     */
    protected $libraryAddress;

    /**
     * Google Maps API Key
     */
    protected $apiKey;

    public function __construct()
    {
        $this->libraryAddress = config('pricing.shipping.library_address', env('LIBRARY_ADDRESS', '123 Đường ABC, Quận XYZ, TP.HCM, Việt Nam'));
        $this->apiKey = config('services.google.maps_api_key');
    }

    /**
     * Tính khoảng cách từ địa chỉ khách hàng đến thư viện
     * 
     * @param string $customerAddress Địa chỉ khách hàng
     * @return array ['distance' => float (km), 'duration' => int (seconds), 'success' => bool]
     */
    public function calculateDistance($customerAddress)
    {
        if (empty($customerAddress)) {
            return [
                'distance' => 0,
                'duration' => 0,
                'success' => false,
                'error' => 'Địa chỉ khách hàng không được để trống'
            ];
        }

        if (empty($this->apiKey)) {
            Log::info('Google Maps API Key chưa được cấu hình - sử dụng phí ship mặc định = 0');
            // Khi không có API key, trả về success với distance = 0 và phí ship = 0
            // Cho phép checkout tiếp tục mà không bị lỗi
            return [
                'distance' => 0,
                'duration' => 0,
                'success' => true,
                'error' => null,
                'message' => 'Google Maps API Key chưa được cấu hình. Phí vận chuyển = 0₫'
            ];
        }

        // Cache key dựa trên địa chỉ khách hàng
        $cacheKey = 'shipping_distance_' . md5($customerAddress . $this->libraryAddress);
        
        // Kiểm tra cache (cache 24 giờ)
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        try {
            // Sử dụng Google Maps Distance Matrix API
            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins' => $this->libraryAddress,
                'destinations' => $customerAddress,
                'key' => $this->apiKey,
                'language' => 'vi',
                'region' => 'vn',
                'units' => 'metric'
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] === 'OK') {
                if (isset($data['rows'][0]['elements'][0]['status']) && 
                    $data['rows'][0]['elements'][0]['status'] === 'OK') {
                    
                    $element = $data['rows'][0]['elements'][0];
                    $distanceKm = $element['distance']['value'] / 1000; // Chuyển từ mét sang km
                    $durationSeconds = $element['duration']['value'];

                    $result = [
                        'distance' => round($distanceKm, 2),
                        'duration' => $durationSeconds,
                        'success' => true,
                        'error' => null
                    ];

                    // Lưu vào cache 24 giờ
                    Cache::put($cacheKey, $result, now()->addHours(24));

                    return $result;
                } else {
                    $status = $data['rows'][0]['elements'][0]['status'] ?? 'UNKNOWN_ERROR';
                    Log::warning('Google Maps API error: ' . $status, [
                        'customer_address' => $customerAddress,
                        'library_address' => $this->libraryAddress
                    ]);

                    return [
                        'distance' => 0,
                        'duration' => 0,
                        'success' => false,
                        'error' => 'Không thể tính khoảng cách. Vui lòng kiểm tra lại địa chỉ.'
                    ];
                }
            } else {
                $status = $data['status'] ?? 'UNKNOWN_ERROR';
                Log::error('Google Maps API request failed', [
                    'status' => $status,
                    'customer_address' => $customerAddress,
                    'library_address' => $this->libraryAddress,
                    'response' => $data
                ]);

                return [
                    'distance' => 0,
                    'duration' => 0,
                    'success' => false,
                    'error' => 'Không thể kết nối đến dịch vụ tính khoảng cách. Vui lòng thử lại sau.'
                ];
            }
        } catch (\Exception $e) {
            Log::error('ShippingService calculateDistance exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'customer_address' => $customerAddress
            ]);

            return [
                'distance' => 0,
                'duration' => 0,
                'success' => false,
                'error' => 'Có lỗi xảy ra khi tính khoảng cách: ' . $e->getMessage()
            ];
        }
    }

    /**
     * QUAN TRONG: Tinh phi van chuyen dua tren khoang cach
     * Logic: Mien phi 5km dau, tu km thu 6 tro di moi km them 5.000 VND
     * 
     * Vi du:
     * - 3km: Mien phi (0 VND)
     * - 7km: (7-5) x 5.000 = 10.000 VND
     * - 10.5km: ceil(10.5-5) x 5.000 = 6 x 5.000 = 30.000 VND
     * 
     * @param float $distance Khoang cach (km)
     * @return float Phi van chuyen (VND)
     */
    public function calculateShippingFee($distance)
    {
        // Lay cau hinh tu config/pricing.php (co the override bang .env)
        $freeKm = config('pricing.shipping.free_km', 5);
        $pricePerKm = config('pricing.shipping.price_per_km', 5000);

        // QUAN TRONG: Neu khoang cach <= km mien phi, phi = 0
        if ($distance <= $freeKm) {
            return 0;
        }

        // Tinh so km phai tra phi (lam tron len de dam bao tinh du phi)
        $extraKm = ceil($distance - $freeKm);
        
        // Phi = so km vuot qua x gia moi km
        $fee = $extraKm * $pricePerKm;

        // QUAN TRONG: Lam tron den hang nghin de de thanh toan
        // VD: 12.500 VND -> 13.000 VND, 12.400 VND -> 12.000 VND
        return round($fee / 1000) * 1000;
    }

    /**
     * QUAN TRONG: Tinh khoang cach va phi van chuyen tu dia chi khach hang
     * 
     * Flow:
     * 1. Tinh khoang cach tu thu vien den dia chi khach hang (dung Google Maps API)
     * 2. Tinh phi van chuyen dua tren khoang cach
     * 3. Tra ve ket qua voi thong tin day du
     * 
     * Luu y: Neu khong co Google Maps API key, van cho phep checkout voi phi = 0
     * 
     * @param string $customerAddress Dia chi khach hang
     * @return array ['distance' => float, 'shipping_fee' => float, 'success' => bool, 'error' => string|null, 'duration' => int]
     */
    public function calculateShipping($customerAddress)
    {
        // Buoc 1: Tinh khoang cach (co cache 24h)
        $distanceResult = $this->calculateDistance($customerAddress);

        // QUAN TRONG: Neu khong thanh cong nhung co message (vi du: khong co API key), van cho phep voi phi = 0
        // De dam bao checkout khong bi loi khi chua cau hinh API key
        if (!$distanceResult['success'] && isset($distanceResult['message'])) {
            return [
                'distance' => 0,
                'shipping_fee' => 0,
                'duration' => 0,
                'success' => true,
                'error' => null,
                'message' => $distanceResult['message']
            ];
        }

        // Neu tinh khoang cach that bai (loi that), tra ve loi
        if (!$distanceResult['success']) {
            return [
                'distance' => 0,
                'shipping_fee' => 0,
                'success' => false,
                'error' => $distanceResult['error'] ?? 'Khong the tinh khoang cach'
            ];
        }

        // Buoc 2: Tinh phi van chuyen dua tren khoang cach
        $distance = $distanceResult['distance'];
        $shippingFee = $this->calculateShippingFee($distance);

        // Tra ve ket qua day du
        return [
            'distance' => $distance,
            'shipping_fee' => $shippingFee,
            'duration' => $distanceResult['duration'],
            'success' => true,
            'error' => null
        ];
    }

    /**
     * Lấy địa chỉ thư viện
     */
    public function getLibraryAddress()
    {
        return $this->libraryAddress;
    }

    /**
     * Set địa chỉ thư viện
     */
    public function setLibraryAddress($address)
    {
        $this->libraryAddress = $address;
    }
}

