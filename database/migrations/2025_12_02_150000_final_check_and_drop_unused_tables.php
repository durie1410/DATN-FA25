<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FinalCheckAndDropUnusedTables extends Migration
{
    /**
     * Run the migrations.
     * Kiểm tra và xóa các bảng không được sử dụng trong codebase
     *
     * @return void
     */
    public function up()
    {
        // Tắt kiểm tra foreign key tạm thời
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Danh sách các bảng ĐƯỢC SỬ DỤNG - KHÔNG XÓA
        $usedTables = [
            // Laravel system tables - KHÔNG BAO GIỜ XÓA
            'migrations',
            'users',
            'password_reset_tokens', // Laravel 9+
            'failed_jobs',
            'personal_access_tokens',
            'notifications', // Laravel notifications system
            
            // Spatie permissions tables - KHÔNG XÓA
            'roles',
            'permissions',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            
            // Application tables với Models - ĐANG ĐƯỢC SỬ DỤNG
            'categories',
            'books',
            'readers',
            'borrows',
            'borrow_items',
            'reviews',
            'comments',
            'favorites',
            'fines',
            'inventories',
            'inventory_transactions',
            'orders',
            'order_items',
            'authors',
            'publishers',
            'faculties',
            'departments',
            'librarians',
            'purchasable_books',
            'vouchers',
            'documents',
            'display_allocations',
            'inventory_receipts',
            'shipping_logs',
            'borrow_payments',
            'borrow_carts',
            'borrow_cart_items',
            'email_campaigns',
            'email_subscribers',
            'email_logs',
            'audit_logs',
            'backups',
            'notification_templates',
            'notification_logs',
            'search_logs',
            
            // Tables được sử dụng trong code nhưng không có Model
            'user_verifications', // Được sử dụng trong PublicBookController và API
        ];
        
        // Lấy danh sách tất cả các bảng trong database
        $allTables = DB::select('SHOW TABLES');
        $databaseName = DB::getDatabaseName();
        $tableKey = 'Tables_in_' . $databaseName;
        
        $tablesToDrop = [];
        
        foreach ($allTables as $table) {
            $tableName = $table->$tableKey;
            
            // Bỏ qua nếu bảng đang được sử dụng
            if (in_array($tableName, $usedTables)) {
                continue;
            }
            
            // Kiểm tra xem bảng có được sử dụng trong code không
            $isUsed = $this->isTableUsedInCode($tableName);
            
            if (!$isUsed) {
                $tablesToDrop[] = $tableName;
            }
        }
        
        // Xóa các bảng không được sử dụng
        if (!empty($tablesToDrop)) {
            foreach ($tablesToDrop as $tableName) {
                try {
                    Schema::dropIfExists($tableName);
                    \Log::info("Đã xóa bảng không sử dụng: {$tableName}");
                } catch (\Exception $e) {
                    \Log::warning("Không thể xóa bảng {$tableName}: {$e->getMessage()}");
                }
            }
        }
        
        // Bật lại kiểm tra foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Kiểm tra xem bảng có được sử dụng trong code không
     */
    private function isTableUsedInCode($tableName)
    {
        // Kiểm tra trong Models - tìm protected $table
        $modelFiles = glob(app_path('Models/*.php'));
        foreach ($modelFiles as $modelFile) {
            $content = file_get_contents($modelFile);
            if (preg_match("/protected\s+\$table\s*=\s*['\"]{$tableName}['\"]/", $content)) {
                return true;
            }
        }
        
        // Kiểm tra trong tất cả PHP files trong app
        $searchPaths = [
            app_path('Http/Controllers'),
            app_path('Services'),
            app_path('Console/Commands'),
        ];
        
        foreach ($searchPaths as $searchPath) {
            if (!is_dir($searchPath)) {
                continue;
            }
            
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($searchPath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $content = file_get_contents($file->getPathname());
                    // Tìm DB::table('table_name'), from('table_name'), join('table_name')
                    if (preg_match("/DB::table\(['\"]{$tableName}['\"]/", $content) ||
                        preg_match("/from\(['\"]{$tableName}['\"]/i", $content) ||
                        preg_match("/join\(['\"]{$tableName}['\"]/i", $content)) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Không cần rollback vì các bảng này không được sử dụng
    }
}
