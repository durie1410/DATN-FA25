<?php
/**
 * Script test Google OAuth sau khi cấu hình
 * Chạy: php test_google_oauth.php
 */

echo "🧪 TEST GOOGLE OAUTH SAU KHI CẤU HÌNH\n";
echo "====================================\n\n";

// Load Laravel environment
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "1. Kiểm tra cấu hình Google OAuth:\n";
$googleConfig = config('services.google');

if (empty($googleConfig['client_id']) || $googleConfig['client_id'] === 'your_google_client_id_here') {
    echo "   ❌ GOOGLE_CLIENT_ID chưa được cấu hình đúng\n";
} else {
    echo "   ✅ GOOGLE_CLIENT_ID đã được cấu hình\n";
}

if (empty($googleConfig['client_secret']) || $googleConfig['client_secret'] === 'your_google_client_secret_here') {
    echo "   ❌ GOOGLE_CLIENT_SECRET chưa được cấu hình đúng\n";
} else {
    echo "   ✅ GOOGLE_CLIENT_SECRET đã được cấu hình\n";
}

if (empty($googleConfig['redirect']) || $googleConfig['redirect'] === 'your_redirect_uri_here') {
    echo "   ❌ GOOGLE_REDIRECT_URI chưa được cấu hình đúng\n";
} else {
    echo "   ✅ GOOGLE_REDIRECT_URI đã được cấu hình: " . $googleConfig['redirect'] . "\n";
}

echo "\n";

echo "2. Kiểm tra routes:\n";
$routes = [
    'auth.google' => 'http://localhost:8000/auth/google',
    'auth.google.callback' => 'http://localhost:8000/auth/google/callback'
];

foreach ($routes as $name => $url) {
    echo "   - $name: $url\n";
}

echo "\n";

echo "3. Kiểm tra controller methods:\n";
if (class_exists('App\Http\Controllers\GoogleAuthController')) {
    $controller = new ReflectionClass('App\Http\Controllers\GoogleAuthController');
    $methods = $controller->getMethods(ReflectionMethod::IS_PUBLIC);
    
    foreach ($methods as $method) {
        if ($method->name !== '__construct') {
            echo "   ✅ Method: " . $method->name . "\n";
        }
    }
} else {
    echo "   ❌ GoogleAuthController không tồn tại\n";
}

echo "\n";

echo "4. Test URLs:\n";
echo "   📝 Đăng ký: http://localhost:8000/register\n";
echo "   🔐 Đăng nhập: http://localhost:8000/login\n";
echo "   🔗 Google OAuth: http://localhost:8000/auth/google\n";

echo "\n";

echo "5. Các bước test:\n";
echo "   1. Đảm bảo server đang chạy: php artisan serve\n";
echo "   2. Truy cập: http://localhost:8000/register\n";
echo "   3. Nhấn nút 'Đăng ký với Google'\n";
echo "   4. Kiểm tra có redirect đến Google không\n";
echo "   5. Nếu có lỗi, kiểm tra lại Google Cloud Console\n";

echo "\n";

echo "📋 CHECKLIST CẤU HÌNH:\n";
echo "=====================\n";
echo "□ File .env có GOOGLE_CLIENT_ID\n";
echo "□ File .env có GOOGLE_CLIENT_SECRET\n";
echo "□ File .env có GOOGLE_REDIRECT_URI\n";
echo "□ Google Cloud Console có redirect URI: http://localhost:8000/auth/google/callback\n";
echo "□ OAuth consent screen đã được cấu hình\n";
echo "□ Cache đã được clear: php artisan config:clear\n";
echo "□ Server đã được restart\n";

echo "\n";

echo "🚨 NẾU VẪN CÒN LỖI:\n";
echo "==================\n";
echo "1. Kiểm tra Google Cloud Console:\n";
echo "   - Redirect URI có khớp không\n";
echo "   - OAuth consent screen có được publish không\n";
echo "   - Client ID và Secret có đúng không\n\n";

echo "2. Kiểm tra file .env:\n";
echo "   - Không có khoảng trắng thừa\n";
echo "   - Không có dấu ngoặc kép thừa\n";
echo "   - URL chính xác: http://localhost:8000/auth/google/callback\n\n";

echo "3. Clear cache và restart:\n";
echo "   php artisan config:clear\n";
echo "   php artisan route:clear\n";
echo "   php artisan serve\n\n";

echo "📖 Xem thêm:\n";
echo "- ENV_CONFIGURATION_GUIDE.md\n";
echo "- GOOGLE_OAUTH_ERROR_FIX.md\n";





