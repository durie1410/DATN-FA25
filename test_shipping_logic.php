<?php
/**
 * Test logic tính phí vận chuyển (không cần Google Maps API)
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\ShippingService;

echo "=== TEST LOGIC TÍNH PHÍ VẬN CHUYỂN ===\n\n";

$shippingService = new ShippingService();

// Test các khoảng cách khác nhau
$testDistances = [
    0 => 0,
    3 => 0,
    5 => 0,
    5.5 => 2500,
    6 => 5000,
    10 => 25000,
    15 => 50000,
    20 => 75000,
];

echo "Test tính phí vận chuyển theo khoảng cách:\n";
echo str_repeat('=', 60) . "\n";
printf("%-15s | %-20s | %-20s\n", "Khoảng cách (km)", "Phí tính được", "Kỳ vọng");
echo str_repeat('-', 60) . "\n";

foreach ($testDistances as $distance => $expectedFee) {
    $fee = $shippingService->calculateShippingFee($distance);
    $status = ($fee == $expectedFee) ? '✓' : '✗';
    printf("%-15s | %-20s | %-20s | %s\n", 
        $distance . ' km', 
        number_format($fee, 0, ',', '.') . ' VNĐ',
        number_format($expectedFee, 0, ',', '.') . ' VNĐ',
        $status
    );
}

echo "\n=== TEST SERVICE TÍNH KHOẢNG CÁCH (Mock) ===\n\n";

// Test với địa chỉ giả (sẽ trả về lỗi vì không có API key)
echo "Test với địa chỉ (không có API key):\n";
$testAddress = "123 Nguyễn Văn A, Quận 1, TP.HCM, Việt Nam";
$result = $shippingService->calculateShipping($testAddress);

if (!$result['success']) {
    echo "✓ Xử lý lỗi đúng: " . $result['error'] . "\n";
    echo "  Distance: " . $result['distance'] . " km\n";
    echo "  Shipping Fee: " . number_format($result['shipping_fee'], 0, ',', '.') . " VNĐ\n";
} else {
    echo "✗ Không mong đợi thành công khi không có API key\n";
}

echo "\n=== TEST VỚI ĐỊA CHỈ RỖNG ===\n\n";
$result = $shippingService->calculateShipping('');
if (!$result['success']) {
    echo "✓ Xử lý đúng khi địa chỉ rỗng: " . $result['error'] . "\n";
} else {
    echo "✗ Không mong đợi thành công khi địa chỉ rỗng\n";
}

echo "\n=== KIỂM TRA CONFIG ===\n\n";
echo "Free KM: " . config('pricing.shipping.free_km', 5) . "\n";
echo "Price per KM: " . number_format(config('pricing.shipping.price_per_km', 5000), 0, ',', '.') . " VNĐ\n";
echo "Library Address: " . config('pricing.shipping.library_address', 'Chưa cấu hình') . "\n";
echo "Google Maps API Key: " . (config('services.google.maps_api_key') ? 'Đã cấu hình' : 'CHƯA CẤU HÌNH') . "\n";

echo "\n=== HOÀN THÀNH ===\n";


