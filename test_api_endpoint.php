<?php
/**
 * Test API endpoint tính phí vận chuyển
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->bootstrap();

use Illuminate\Http\Request;

echo "=== TEST API ENDPOINT ===\n\n";

// Tạo request giả với JSON body
$jsonData = json_encode(['address' => '123 Nguyễn Văn A, Quận 1, TP.HCM, Việt Nam']);
$request = Request::create('/api/shipping/calculate', 'POST', [], [], [], [
    'HTTP_ACCEPT' => 'application/json',
    'CONTENT_TYPE' => 'application/json',
    'CONTENT_LENGTH' => strlen($jsonData)
], $jsonData);

$request->headers->set('Accept', 'application/json');
$request->headers->set('Content-Type', 'application/json');

try {
    $response = $kernel->handle($request);
    $statusCode = $response->getStatusCode();
    $content = $response->getContent();
    $data = json_decode($content, true);
    
    echo "Status Code: {$statusCode}\n";
    echo "Response:\n";
    print_r($data);
    
    if ($statusCode === 200 || $statusCode === 400) {
        if (isset($data['success']) && !$data['success']) {
            echo "\n✓ API trả về lỗi đúng khi không có Google Maps API Key\n";
            echo "  Message: " . ($data['message'] ?? 'N/A') . "\n";
        } else if (isset($data['success']) && $data['success']) {
            echo "\n✓ API hoạt động thành công!\n";
            echo "  Distance: " . ($data['distance'] ?? 0) . " km\n";
            echo "  Shipping Fee: " . number_format($data['shipping_fee'] ?? 0, 0, ',', '.') . " VNĐ\n";
        }
    } else {
        echo "\n✗ Status code không mong đợi: {$statusCode}\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST VALIDATION ===\n\n";

// Test với địa chỉ rỗng
$request2 = Request::create('/api/shipping/calculate', 'POST', [
    'address' => ''
], [], [], [
    'HTTP_ACCEPT' => 'application/json',
    'CONTENT_TYPE' => 'application/json'
]);

$request2->headers->set('Accept', 'application/json');
$request2->headers->set('Content-Type', 'application/json');

try {
    $response2 = $kernel->handle($request2);
    $statusCode2 = $response2->getStatusCode();
    $content2 = $response2->getContent();
    $data2 = json_decode($content2, true);
    
    echo "Test với địa chỉ rỗng:\n";
    echo "Status Code: {$statusCode2}\n";
    
    if ($statusCode2 === 422) {
        echo "✓ Validation hoạt động đúng (422 Unprocessable Entity)\n";
        if (isset($data2['errors'])) {
            echo "  Errors: " . json_encode($data2['errors'], JSON_UNESCAPED_UNICODE) . "\n";
        }
    } else {
        echo "✗ Không mong đợi status code: {$statusCode2}\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
}

echo "\n=== HOÀN THÀNH ===\n";

