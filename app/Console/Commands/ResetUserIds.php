<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ResetUserIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-ids {--force : Force reset without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset user IDs to start from 1 and update all related foreign keys';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  CẢNH BÁO: Lệnh này sẽ thay đổi ID của tất cả người dùng và cập nhật các bảng liên quan. Bạn có chắc chắn muốn tiếp tục?')) {
                $this->info('Đã hủy.');
                return 0;
            }
        }

        $this->info('🔄 Bắt đầu reset user IDs...');

        try {
            // Get all users ordered by current ID
            $users = User::orderBy('id')->get();
            $totalUsers = $users->count();

            if ($totalUsers == 0) {
                $this->info('Không có người dùng nào để reset.');
                return 0;
            }

            $this->info("Tìm thấy {$totalUsers} người dùng.");

            // Create mapping: old_id => new_id
            $idMapping = [];
            $newId = 1;
            foreach ($users as $user) {
                $idMapping[$user->id] = $newId;
                $newId++;
            }

            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            try {
                // Update all tables with user_id foreign keys
                $this->updateTableForeignKeys($idMapping);

                // Update users table IDs - need to use temporary IDs to avoid conflicts
                $this->info('📝 Đang cập nhật ID trong bảng users...');
                
                // First, update to temporary IDs (add 100000 to avoid conflicts)
                foreach ($users as $user) {
                    $newId = $idMapping[$user->id];
                    if ($user->id != $newId) {
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['id' => $newId + 100000]);
                    }
                }

                // Then update to final IDs
                foreach ($users as $user) {
                    $newId = $idMapping[$user->id];
                    if ($user->id != $newId) {
                        DB::table('users')
                            ->where('id', $newId + 100000)
                            ->update(['id' => $newId]);
                    }
                }

                // Reset auto-increment
                $maxId = DB::table('users')->max('id');
                $newAutoIncrement = $maxId ? $maxId + 1 : 1;
                DB::statement("ALTER TABLE users AUTO_INCREMENT = {$newAutoIncrement}");

                $this->info("✅ Đã reset thành công! ID hiện tại từ 1 đến {$maxId}");
                $this->info("Auto-increment được đặt về: {$newAutoIncrement}");

            } finally {
                // Always re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }

            return 0;
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('❌ Lỗi: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Update foreign keys in all related tables
     */
    private function updateTableForeignKeys(array $idMapping)
    {
        $tables = [
            'readers' => 'user_id',
            'reservations' => 'user_id',
            'reviews' => 'user_id',
            'borrows' => 'librarian_id',
            'loans' => 'user_id',
            'orders' => 'user_id',
            'fines' => 'user_id',
            'comments' => 'user_id',
            'audit_logs' => 'user_id',
            'user_verifications' => 'user_id',
            'favorites' => 'user_id',
            'payments' => 'user_id',
            'deposits' => 'user_id',
            'seat_reservations' => 'user_id',
        ];

        foreach ($tables as $table => $column) {
            if (DB::getSchemaBuilder()->hasTable($table) && DB::getSchemaBuilder()->hasColumn($table, $column)) {
                $this->info("📝 Đang cập nhật bảng {$table}...");
                
                // Use temporary mapping to avoid conflicts
                foreach ($idMapping as $oldId => $newId) {
                    if ($oldId != $newId) {
                        // First update to temporary value
                        DB::table($table)
                            ->where($column, $oldId)
                            ->update([$column => $newId + 100000]);
                    }
                }
                
                // Then update to final value
                foreach ($idMapping as $oldId => $newId) {
                    if ($oldId != $newId) {
                        DB::table($table)
                            ->where($column, $newId + 100000)
                            ->update([$column => $newId]);
                    }
                }
            }
        }
    }
}
