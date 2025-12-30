<?php

/**
 * Route tạm thời để thêm các cột xác nhận khách hàng
 * Truy cập: http://quanlythuviennn.test/fix-customer-confirmation
 * 
 * SAU KHI CHẠY XONG, XÓA FILE NÀY ĐỂ BẢO MẬT!
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/fix-customer-confirmation', function () {
    // Chỉ cho phép trong môi trường local
    if (app()->environment('production')) {
        abort(403, 'This route is only available in local environment');
    }

    $results = [];
    $errors = [];
    
    try {
        // Kiểm tra bảng borrows
        if (!Schema::hasTable('borrows')) {
            return response('❌ LỖI: Bảng borrows không tồn tại!', 500);
        }
        
        $results[] = '✓ Bảng borrows đã tồn tại';
        
        // Thêm cột customer_confirmed_delivery
        if (Schema::hasColumn('borrows', 'customer_confirmed_delivery')) {
            $results[] = '✓ Cột customer_confirmed_delivery đã tồn tại';
        } else {
            try {
                DB::statement("
                    ALTER TABLE borrows 
                    ADD COLUMN customer_confirmed_delivery TINYINT(1) NOT NULL DEFAULT 0 
                    COMMENT 'Khách hàng đã xác nhận nhận sách'
                ");
                $results[] = '✓ Đã thêm cột customer_confirmed_delivery thành công!';
            } catch (\Exception $e) {
                $errors[] = '✗ Lỗi khi thêm customer_confirmed_delivery: ' . $e->getMessage();
            }
        }
        
        // Thêm cột customer_confirmed_delivery_at
        if (Schema::hasColumn('borrows', 'customer_confirmed_delivery_at')) {
            $results[] = '✓ Cột customer_confirmed_delivery_at đã tồn tại';
        } else {
            try {
                DB::statement("
                    ALTER TABLE borrows 
                    ADD COLUMN customer_confirmed_delivery_at TIMESTAMP NULL DEFAULT NULL 
                    COMMENT 'Thời gian khách hàng xác nhận nhận sách'
                ");
                $results[] = '✓ Đã thêm cột customer_confirmed_delivery_at thành công!';
            } catch (\Exception $e) {
                $errors[] = '✗ Lỗi khi thêm customer_confirmed_delivery_at: ' . $e->getMessage();
            }
        }
        
        // Kiểm tra lại
        $finalCheck = [];
        if (Schema::hasColumn('borrows', 'customer_confirmed_delivery')) {
            $finalCheck[] = '✓ customer_confirmed_delivery: CÓ';
        } else {
            $finalCheck[] = '✗ customer_confirmed_delivery: CHƯA CÓ';
        }
        
        if (Schema::hasColumn('borrows', 'customer_confirmed_delivery_at')) {
            $finalCheck[] = '✓ customer_confirmed_delivery_at: CÓ';
        } else {
            $finalCheck[] = '✗ customer_confirmed_delivery_at: CHƯA CÓ';
        }
        
        // Hiển thị kết quả
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa lỗi - Thêm cột xác nhận khách hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .success {
            color: #4CAF50;
            background: #e8f5e9;
            padding: 10px;
            border-left: 4px solid #4CAF50;
            margin: 10px 0;
        }
        .error {
            color: #f44336;
            background: #ffebee;
            padding: 10px;
            border-left: 4px solid #f44336;
            margin: 10px 0;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Sửa lỗi: Thêm cột xác nhận khách hàng</h1>';
        
        if (!empty($results)) {
            $html .= '<h2>Kết quả:</h2>';
            foreach ($results as $result) {
                $html .= '<div class="success">' . htmlspecialchars($result) . '</div>';
            }
        }
        
        if (!empty($errors)) {
            $html .= '<h2>Lỗi:</h2>';
            foreach ($errors as $error) {
                $html .= '<div class="error">' . htmlspecialchars($error) . '</div>';
            }
        }
        
        $html .= '<h2>Kiểm tra cuối cùng:</h2>';
        foreach ($finalCheck as $check) {
            $class = strpos($check, '✓') !== false ? 'success' : 'error';
            $html .= '<div class="' . $class . '">' . htmlspecialchars($check) . '</div>';
        }
        
        $allSuccess = Schema::hasColumn('borrows', 'customer_confirmed_delivery') && 
                     Schema::hasColumn('borrows', 'customer_confirmed_delivery_at');
        
        if ($allSuccess) {
            $html .= '<div class="info">
                <h3>✅ HOÀN TẤT!</h3>
                <p>Các cột đã được thêm thành công vào bảng borrows.</p>
                <p>Bây giờ bạn có thể:</p>
                <ul>
                    <li>Làm mới trang web (F5)</li>
                    <li>Lỗi sẽ biến mất</li>
                    <li>Tính năng xác nhận 2 chiều sẽ hoạt động bình thường</li>
                </ul>
                <a href="/account/borrowed-books" class="button">Quay lại trang Sách đang mượn</a>
            </div>';
        } else {
            $html .= '<div class="error">
                <h3>⚠️ Có vấn đề!</h3>
                <p>Một số cột chưa được thêm thành công. Vui lòng kiểm tra lại hoặc chạy SQL trực tiếp trong phpMyAdmin.</p>
            </div>';
        }
        
        $html .= '<div class="info" style="margin-top: 30px; font-size: 12px; color: #666;">
            <p><strong>Lưu ý:</strong> Sau khi sửa xong, vui lòng xóa file <code>routes/fix_customer_confirmation.php</code> để bảo mật!</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
        
    } catch (\Exception $e) {
        return response('❌ LỖI: ' . $e->getMessage(), 500);
    }
})->name('fix.customer.confirmation');
