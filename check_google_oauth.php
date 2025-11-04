<?php
/**
 * Script kiểm tra cấu hình Google OAuth
 * Chạy: php check_google_oauth.php
 */

echo "🔍 KIỂM TRA CẤU HÌNH GOOGLE OAUTH\n";
echo "================================\n\n";

// Kiểm tra file .env
echo "1. Kiểm tra file .env:\n";
if (file_exists('.env')) {
    echo "   ✅ File .env tồn tại\n";
    
    $envContent = file_get_contents('.env');
    
    // Kiểm tra các biến cần thiết
    $requiredVars = [
        'GOOGLE_CLIENT_ID',
        'GOOGLE_CLIENT_SECRET', 
        'GOOGLE_REDIRECT_URI'
    ];
    
    foreach ($requiredVars as $var) {
        if (strpos($envContent, $var) !== false) {
            echo "   ✅ $var được cấu hình\n";
        } else {
            echo "   ❌ $var CHƯA được cấu hình\n";
        }
    }
} else {
    echo "   ❌ File .env KHÔNG tồn tại\n";
}

echo "\n";

// Kiểm tra cấu hình services.php
echo "2. Kiểm tra config/services.php:\n";
if (file_exists('config/services.php')) {
    echo "   ✅ File config/services.php tồn tại\n";
    
    $config = include 'config/services.php';
    if (isset($config['google'])) {
        echo "   ✅ Cấu hình Google OAuth tồn tại\n";
        
        $googleConfig = $config['google'];
        echo "   - Client ID: " . (empty($googleConfig['client_id']) ? '❌ CHƯA SET' : '✅ ĐÃ SET') . "\n";
        echo "   - Client Secret: " . (empty($googleConfig['client_secret']) ? '❌ CHƯA SET' : '✅ ĐÃ SET') . "\n";
        echo "   - Redirect URI: " . (empty($googleConfig['redirect']) ? '❌ CHƯA SET' : '✅ ĐÃ SET') . "\n";
        
        if (!empty($googleConfig['redirect'])) {
            echo "   - Redirect URI hiện tại: " . $googleConfig['redirect'] . "\n";
        }
    } else {
        echo "   ❌ Cấu hình Google OAuth KHÔNG tồn tại\n";
    }
} else {
    echo "   ❌ File config/services.php KHÔNG tồn tại\n";
}

echo "\n";

// Kiểm tra routes
echo "3. Kiểm tra routes:\n";
$routes = [
    'auth.google' => '/auth/google',
    'auth.google.callback' => '/auth/google/callback'
];

foreach ($routes as $name => $path) {
    echo "   - Route $name ($path): ";
    // Kiểm tra file routes/web.php
    if (file_exists('routes/web.php')) {
        $routeContent = file_get_contents('routes/web.php');
        if (strpos($routeContent, $name) !== false) {
            echo "✅ ĐÃ ĐĂNG KÝ\n";
        } else {
            echo "❌ CHƯA ĐĂNG KÝ\n";
        }
    } else {
        echo "❌ File routes/web.php KHÔNG tồn tại\n";
    }
}

echo "\n";

// Kiểm tra controller
echo "4. Kiểm tra GoogleAuthController:\n";
if (file_exists('app/Http/Controllers/GoogleAuthController.php')) {
    echo "   ✅ GoogleAuthController tồn tại\n";
} else {
    echo "   ❌ GoogleAuthController KHÔNG tồn tại\n";
}

echo "\n";

// Hướng dẫn khắc phục
echo "📋 HƯỚNG DẪN KHẮC PHỤC:\n";
echo "=======================\n";
echo "1. Đảm bảo file .env có các biến:\n";
echo "   GOOGLE_CLIENT_ID=your_client_id\n";
echo "   GOOGLE_CLIENT_SECRET=your_client_secret\n";
echo "   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback\n\n";

echo "2. Cấu hình Google Cloud Console:\n";
echo "   - Truy cập: https://console.cloud.google.com/\n";
echo "   - APIs & Services → Credentials\n";
echo "   - Thêm redirect URI: http://localhost:8000/auth/google/callback\n\n";

echo "3. Clear cache:\n";
echo "   php artisan config:clear\n";
echo "   php artisan route:clear\n\n";

echo "4. Restart server:\n";
echo "   php artisan serve\n\n";

echo "5. Test:\n";
echo "   - Truy cập: http://localhost:8000/register\n";
echo "   - Nhấn nút 'Đăng ký với Google'\n\n";

echo "📖 Xem thêm: GOOGLE_OAUTH_ERROR_FIX.md\n";





