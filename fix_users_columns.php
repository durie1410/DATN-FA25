<?php

// Kết nối database trực tiếp
$host = 'localhost';
$dbname = 'quanlythuviennn'; // Thay đổi nếu cần
$username = 'root'; // Thay đổi nếu cần
$password = ''; // Thay đổi nếu cần

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Đang kiểm tra và thêm các cột vào bảng users...\n\n";
    
    // Kiểm tra cột ngay_sinh
    $stmt = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'ngay_sinh'");
    if ($stmt->rowCount() == 0) {
        echo "Thêm cột ngay_sinh...\n";
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `so_cccd`");
        echo "✓ Đã thêm cột ngay_sinh thành công!\n\n";
    } else {
        echo "✓ Cột ngay_sinh đã tồn tại\n\n";
    }
    
    // Kiểm tra cột gioi_tinh
    $stmt = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'gioi_tinh'");
    if ($stmt->rowCount() == 0) {
        echo "Thêm cột gioi_tinh...\n";
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `ngay_sinh`");
        echo "✓ Đã thêm cột gioi_tinh thành công!\n\n";
    } else {
        echo "✓ Cột gioi_tinh đã tồn tại\n\n";
    }
    
    echo "Hoàn thành! Các cột đã được thêm vào bảng users.\n";
    echo "Bây giờ bạn có thể cập nhật thông tin tài khoản mà không gặp lỗi.\n";
    
} catch (PDOException $e) {
    echo "Lỗi kết nối database: " . $e->getMessage() . "\n";
    echo "\nVui lòng kiểm tra:\n";
    echo "1. Tên database: $dbname\n";
    echo "2. Username: $username\n";
    echo "3. Password: (đã được ẩn)\n";
    echo "4. Host: $host\n";
    echo "\nNếu thông tin không đúng, vui lòng chỉnh sửa file fix_users_columns.php\n";
}

