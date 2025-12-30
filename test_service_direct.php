<?php
/**
 * Test trực tiếp ShippingService
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\ShippingService;

echo "=== TEST SHIPPING SERVICE ===\n\n";

$service = new ShippingService();

echo "1. Library Address:\n";
echo "   " . $service->getLibraryAddress() . "\n\n";

echo "2. Test calculateShippingFee:\n";
$testCases = [0, 3, 5, 6, 10, 15, 20];
foreach ($testCases as $km) {
    $fee = $service->calculateShippingFee($km);
    echo "   {$km}km = " . number_format($fee, 0, ',', '.') . " VNĐ\n";
}

echo "\n3. Test calculateShipping (không có API key):\n";
$result = $service->calculateShipping("123 Nguyễn Văn A, Quận 1, TP.HCM");
if (!$result['success']) {
    echo "   ✓ Xử lý lỗi đúng: " . $result['error'] . "\n";
} else {
    echo "   ✗ Không mong đợi thành công\n";
}

echo "\n=== HOÀN THÀNH ===\n";


