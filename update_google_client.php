<?php
/**
 * Script cập nhật Google OAuth Client ID
 * Chạy: php update_google_client.php
 */

echo "🔧 CẬP NHẬT GOOGLE OAUTH CLIENT ID\n";
echo "==================================\n\n";

echo "📋 HƯỚNG DẪN TẠO GOOGLE OAUTH CLIENT ID:\n";
echo "=======================================\n";
echo "1. Truy cập: https://console.cloud.google.com/\n";
echo "2. Tạo project mới hoặc chọn project hiện có\n";
echo "3. Vào APIs & Services → Library → Enable Google+ API\n";
echo "4. Vào APIs & Services → Credentials\n";
echo "5. Nhấn + CREATE CREDENTIALS → OAuth client ID\n";
echo "6. Application type: Web application\n";
echo "7. Name: Thư viện OAuth\n";
echo "8. Authorized JavaScript origins: http://localhost:8000\n";
echo "9. Authorized redirect URIs: http://localhost:8000/auth/google/callback\n";
echo "10. Nhấn CREATE và copy Client ID + Client Secret\n\n";

echo "📝 SAU KHI CÓ CLIENT ID VÀ SECRET:\n";
echo "==================================\n";
echo "Chạy lệnh sau để cập nhật:\n";
echo "php update_google_client.php YOUR_CLIENT_ID YOUR_CLIENT_SECRET\n\n";

echo "Ví dụ:\n";
echo "php update_google_client.php 123456789-abcdefghijklmnop.apps.googleusercontent.com GOCSPX-abcdefghijklmnopqrstuvwxyz\n\n";

// Kiểm tra arguments
if ($argc < 3) {
    echo "❌ THIẾU THAM SỐ!\n";
    echo "Sử dụng: php update_google_client.php CLIENT_ID CLIENT_SECRET\n";
    exit(1);
}

$clientId = $argv[1];
$clientSecret = $argv[2];

echo "🔄 ĐANG CẬP NHẬT FILE .ENV...\n";
echo "=============================\n";

// Đọc file .env
$envContent = file_get_contents('.env');
if ($envContent === false) {
    echo "❌ Không thể đọc file .env\n";
    exit(1);
}

// Cập nhật Client ID
$envContent = preg_replace(
    '/GOOGLE_CLIENT_ID=.*/',
    'GOOGLE_CLIENT_ID=' . $clientId,
    $envContent
);

// Cập nhật Client Secret
$envContent = preg_replace(
    '/GOOGLE_CLIENT_SECRET=.*/',
    'GOOGLE_CLIENT_SECRET=' . $clientSecret,
    $envContent
);

// Ghi lại file .env
if (file_put_contents('.env', $envContent) === false) {
    echo "❌ Không thể ghi file .env\n";
    exit(1);
}

echo "✅ Đã cập nhật GOOGLE_CLIENT_ID: " . $clientId . "\n";
echo "✅ Đã cập nhật GOOGLE_CLIENT_SECRET: " . $clientSecret . "\n\n";

echo "🔄 ĐANG CLEAR CACHE...\n";
echo "=====================\n";

// Clear cache
exec('php artisan config:clear', $output, $returnCode);
if ($returnCode === 0) {
    echo "✅ Config cache đã được clear\n";
} else {
    echo "❌ Lỗi khi clear config cache\n";
}

exec('php artisan route:clear', $output, $returnCode);
if ($returnCode === 0) {
    echo "✅ Route cache đã được clear\n";
} else {
    echo "❌ Lỗi khi clear route cache\n";
}

echo "\n";

echo "🎯 KIỂM TRA CẤU HÌNH:\n";
echo "====================\n";

// Kiểm tra cấu hình
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$googleConfig = config('services.google');
echo "Client ID: " . $googleConfig['client_id'] . "\n";
echo "Client Secret: " . $googleConfig['client_secret'] . "\n";
echo "Redirect URI: " . $googleConfig['redirect'] . "\n\n";

echo "🚀 BƯỚC TIẾP THEO:\n";
echo "================\n";
echo "1. Start server: php artisan serve\n";
echo "2. Truy cập: http://localhost:8000/register\n";
echo "3. Nhấn 'Đăng ký với Google'\n";
echo "4. Kiểm tra không còn lỗi 'invalid_client'\n\n";

echo "✅ HOÀN THÀNH! Google OAuth đã được cấu hình.\n";





