<?php
echo "=== FIX VNPAY - CACH DON GIAN ===\n\n";

// Đọc file .env
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    die("ERROR: File .env khong ton tai!\n");
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES);
$updated = false;
$foundTMN = false;
$foundHash = false;
$foundURL = false;

// Cập nhật các dòng
foreach ($lines as $key => $line) {
    if (strpos($line, 'VNPAY_TMN_CODE=') === 0) {
        $lines[$key] = 'VNPAY_TMN_CODE=E6I8Z7HX';
        $foundTMN = true;
        $updated = true;
    }
    if (strpos($line, 'VNPAY_HASH_SECRET=') === 0) {
        $lines[$key] = 'VNPAY_HASH_SECRET=LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ';
        $foundHash = true;
        $updated = true;
    }
    if (strpos($line, 'VNPAY_URL=') === 0) {
        $lines[$key] = 'VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $foundURL = true;
        $updated = true;
    }
}

// Thêm nếu chưa có
if (!$foundTMN) {
    $lines[] = 'VNPAY_TMN_CODE=E6I8Z7HX';
    $updated = true;
}
if (!$foundHash) {
    $lines[] = 'VNPAY_HASH_SECRET=LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ';
    $updated = true;
}
if (!$foundURL) {
    $lines[] = 'VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    $updated = true;
}

// Ghi file
if ($updated) {
    file_put_contents($envFile, implode("\n", $lines));
    echo "[OK] Da cap nhat file .env\n";
} else {
    echo "[OK] File .env da dung, khong can cap nhat\n";
}

echo "\n";
echo "TMN_CODE: E6I8Z7HX\n";
echo "HASH_SECRET: LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ\n";
echo "URL: https://sandbox.vnpayment.vn/paymentv2/vpcpay.html\n";
echo "\n";

// Clear cache
echo "Dang clear cache...\n";
passthru('php artisan config:clear 2>&1');
echo "\n";
passthru('php artisan cache:clear 2>&1');
echo "\n";

echo "=== HOAN THANH ===\n";
echo "Hay refresh trang va thu lai!\n";

