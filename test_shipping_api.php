<?php
/**
 * Script test API tính phí vận chuyển
 * Chạy: php test_shipping_api.php
 */

// Địa chỉ test
$testAddresses = [
    '123 Nguyễn Văn A, Quận 1, TP.HCM, Việt Nam',
    '456 Lê Lợi, Quận 3, TP.HCM, Việt Nam',
    '789 Trần Hưng Đạo, Quận 5, TP.HCM, Việt Nam',
];

// URL API (thay đổi theo domain của bạn)
$apiUrl = 'http://localhost/api/shipping/calculate';

echo "=== TEST API TÍNH PHÍ VẬN CHUYỂN ===\n\n";

foreach ($testAddresses as $index => $address) {
    echo "Test " . ($index + 1) . ": {$address}\n";
    echo str_repeat('-', 60) . "\n";
    
    // Tạo request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['address' => $address]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data['success']) {
            echo "✓ Thành công!\n";
            echo "  Khoảng cách: {$data['distance']} km\n";
            echo "  Phí vận chuyển: " . number_format($data['shipping_fee'], 0, ',', '.') . " VNĐ\n";
            echo "  Thời gian: " . round($data['duration'] / 60, 1) . " phút\n";
        } else {
            echo "✗ Lỗi: {$data['message']}\n";
        }
    } else {
        echo "✗ HTTP Error: {$httpCode}\n";
        echo "  Response: {$response}\n";
    }
    
    echo "\n";
}

echo "=== HOÀN THÀNH ===\n";


