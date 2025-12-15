<?php

echo "==============================================\n";
echo "   KIỂM TRA VÀ SỬA LỖI VNPAY\n";
echo "==============================================\n\n";

// Thông tin VNPay từ sandbox
$VNPAY_TMN_CODE = 'E6I8Z7HX';
$VNPAY_HASH_SECRET = 'LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ';
$VNPAY_URL = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    die("❌ File .env không tồn tại!\n");
}

$content = file_get_contents($envFile);
$updated = false;

// Cập nhật hoặc thêm VNPAY_TMN_CODE
if (preg_match('/VNPAY_TMN_CODE=.*/', $content)) {
    $content = preg_replace('/VNPAY_TMN_CODE=.*/', 'VNPAY_TMN_CODE=' . $VNPAY_TMN_CODE, $content);
    echo "✅ Đã cập nhật VNPAY_TMN_CODE\n";
    $updated = true;
} else {
    $content .= "\nVNPAY_TMN_CODE=" . $VNPAY_TMN_CODE;
    echo "✅ Đã thêm VNPAY_TMN_CODE\n";
    $updated = true;
}

// Cập nhật hoặc thêm VNPAY_HASH_SECRET
if (preg_match('/VNPAY_HASH_SECRET=.*/', $content)) {
    $content = preg_replace('/VNPAY_HASH_SECRET=.*/', 'VNPAY_HASH_SECRET=' . $VNPAY_HASH_SECRET, $content);
    echo "✅ Đã cập nhật VNPAY_HASH_SECRET\n";
    $updated = true;
} else {
    $content .= "\nVNPAY_HASH_SECRET=" . $VNPAY_HASH_SECRET;
    echo "✅ Đã thêm VNPAY_HASH_SECRET\n";
    $updated = true;
}

// Cập nhật hoặc thêm VNPAY_URL
if (preg_match('/VNPAY_URL=.*/', $content)) {
    $content = preg_replace('/VNPAY_URL=.*/', 'VNPAY_URL=' . $VNPAY_URL, $content);
    echo "✅ Đã cập nhật VNPAY_URL\n";
    $updated = true;
} else {
    $content .= "\nVNPAY_URL=" . $VNPAY_URL;
    echo "✅ Đã thêm VNPAY_URL\n";
    $updated = true;
}

if ($updated) {
    file_put_contents($envFile, $content);
    echo "\n🎉 Cập nhật file .env thành công!\n\n";
}

echo "📋 Thông tin VNPay đã cấu hình:\n";
echo "   TMN_CODE: $VNPAY_TMN_CODE\n";
echo "   HASH_SECRET: " . substr($VNPAY_HASH_SECRET, 0, 10) . "...\n";
echo "   URL: $VNPAY_URL\n\n";

echo "⚡ Tiếp theo, chạy các lệnh sau:\n";
echo "   1. php artisan config:clear\n";
echo "   2. php artisan cache:clear\n";
echo "   3. Thử thanh toán lại\n\n";

// Kiểm tra xem có thể load được Laravel không
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "🔍 Kiểm tra config hiện tại:\n";
    $config = config('services.vnpay');
    echo "   TMN_CODE từ config: " . ($config['tmn_code'] ?: '(trống)') . "\n";
    echo "   HASH_SECRET có giá trị: " . (!empty($config['hash_secret']) ? 'Có (' . strlen($config['hash_secret']) . ' ký tự)' : 'Không') . "\n";
    
    if ($config['tmn_code'] !== $VNPAY_TMN_CODE || $config['hash_secret'] !== $VNPAY_HASH_SECRET) {
        echo "\n⚠️  LƯU Ý: Config cache cũ vẫn đang được dùng!\n";
        echo "   Hãy chạy: php artisan config:clear\n";
    } else {
        echo "\n✅ Config đã được load đúng!\n";
    }
}

echo "\n==============================================\n";

