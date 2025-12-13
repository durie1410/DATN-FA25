<?php

// Script tạm thời để cập nhật file .env
$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    die("❌ File .env không tồn tại!\n");
}

$content = file_get_contents($envFile);

// Cập nhật hoặc thêm VNPAY_TMN_CODE
if (preg_match('/VNPAY_TMN_CODE=.*/', $content)) {
    $content = preg_replace('/VNPAY_TMN_CODE=.*/', 'VNPAY_TMN_CODE=E6I8Z7HX', $content);
    echo "✅ Đã cập nhật VNPAY_TMN_CODE\n";
} else {
    $content .= "\nVNPAY_TMN_CODE=E6I8Z7HX";
    echo "✅ Đã thêm VNPAY_TMN_CODE\n";
}

// Cập nhật hoặc thêm VNPAY_HASH_SECRET
if (preg_match('/VNPAY_HASH_SECRET=.*/', $content)) {
    $content = preg_replace('/VNPAY_HASH_SECRET=.*/', 'VNPAY_HASH_SECRET=LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ', $content);
    echo "✅ Đã cập nhật VNPAY_HASH_SECRET\n";
} else {
    $content .= "\nVNPAY_HASH_SECRET=LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ";
    echo "✅ Đã thêm VNPAY_HASH_SECRET\n";
}

// Cập nhật hoặc thêm VNPAY_URL
if (preg_match('/VNPAY_URL=.*/', $content)) {
    $content = preg_replace('/VNPAY_URL=.*/', 'VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html', $content);
    echo "✅ Đã cập nhật VNPAY_URL\n";
} else {
    $content .= "\nVNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    echo "✅ Đã thêm VNPAY_URL\n";
}

// Lưu file
file_put_contents($envFile, $content);

echo "\n🎉 Cập nhật file .env thành công!\n";
echo "\n📋 Giá trị đã cập nhật:\n";
echo "   VNPAY_TMN_CODE=E6I8Z7HX\n";
echo "   VNPAY_HASH_SECRET=LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ\n";
echo "   VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html\n";
echo "\n⚡ Tiếp theo, chạy lệnh: php artisan config:clear\n";


