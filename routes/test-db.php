<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/test-db-structure', function() {
    try {
        // Kiểm tra cấu trúc bảng borrow_items
        $columns = DB::select("SHOW COLUMNS FROM borrow_items WHERE Field = 'trang_thai'");
        
        echo "<h2>Cấu trúc cột trang_thai:</h2>";
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
        
        // Thử insert test
        echo "<h2>Test Insert:</h2>";
        try {
            $testId = DB::table('borrow_items')->insertGetId([
                'borrow_id' => 1,
                'book_id' => 1,
                'tien_coc' => 0,
                'tien_thue' => 0,
                'tien_ship' => 0,
                'ngay_hen_tra' => now()->addDays(7),
                'trang_thai' => 'Cho duyet',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✓ Insert thành công với ID: $testId<br>";
            
            // Test update
            $affected = DB::table('borrow_items')
                ->where('id', $testId)
                ->update(['trang_thai' => 'Dang muon']);
            echo "✓ Update thành công, affected rows: $affected<br>";
            
            // Kiểm tra
            $item = DB::table('borrow_items')->where('id', $testId)->first();
            echo "✓ Trạng thái sau update: " . $item->trang_thai . "<br>";
            
            // Xóa test data
            DB::table('borrow_items')->where('id', $testId)->delete();
            echo "✓ Đã xóa test data<br>";
            
        } catch (\Exception $e) {
            echo "✗ Lỗi: " . $e->getMessage() . "<br>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        // Kiểm tra các item hiện có
        echo "<h2>Các BorrowItem hiện có:</h2>";
        $items = DB::table('borrow_items')
            ->select('id', 'borrow_id', 'book_id', 'trang_thai')
            ->limit(10)
            ->get();
        echo "<pre>";
        print_r($items);
        echo "</pre>";
        
    } catch (\Exception $e) {
        echo "<h2>Lỗi:</h2>";
        echo $e->getMessage();
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});


