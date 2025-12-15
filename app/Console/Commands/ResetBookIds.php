<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

class ResetBookIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:reset-ids {--force : Force reset without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset book IDs to be continuous from 1 and update all related foreign keys';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  CẢNH BÁO: Lệnh này sẽ thay đổi ID của tất cả sách và cập nhật các bảng liên quan.');
            $this->warn('⚠️  Hãy đảm bảo bạn đã backup database trước khi chạy lệnh này!');
            if (!$this->confirm('Bạn có chắc chắn muốn tiếp tục?')) {
                $this->info('Đã hủy.');
                return 0;
            }
        }

        $this->info('🔄 Bắt đầu sắp xếp lại ID sách...');

        try {
            // Get all books ordered by current ID
            $books = Book::orderBy('id')->get();
            $totalBooks = $books->count();

            if ($totalBooks == 0) {
                $this->info('Không có sách nào để sắp xếp.');
                return 0;
            }

            $this->info("Tìm thấy {$totalBooks} sách.");

            // Create mapping: old_id => new_id
            $idMapping = [];
            $newId = 1;
            foreach ($books as $book) {
                $idMapping[$book->id] = $newId;
                $newId++;
            }

            $this->info("Sẽ sắp xếp lại từ ID 1 đến {$totalBooks}.");

            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            try {
                // Update all tables with book_id foreign keys
                $this->updateTableForeignKeys($idMapping);

                // Update books table IDs - need to use temporary IDs to avoid conflicts
                $this->info('📝 Đang cập nhật ID trong bảng books...');
                
                // First, update to temporary IDs (add 1000000 to avoid conflicts)
                foreach ($books as $book) {
                    $newId = $idMapping[$book->id];
                    if ($book->id != $newId) {
                        DB::table('books')
                            ->where('id', $book->id)
                            ->update(['id' => $newId + 1000000]);
                    }
                }

                // Then update to final IDs
                foreach ($books as $book) {
                    $newId = $idMapping[$book->id];
                    if ($book->id != $newId) {
                        DB::table('books')
                            ->where('id', $newId + 1000000)
                            ->update(['id' => $newId]);
                    }
                }

                // Reset auto-increment
                $maxId = DB::table('books')->max('id');
                $newAutoIncrement = $maxId ? $maxId + 1 : 1;
                DB::statement("ALTER TABLE books AUTO_INCREMENT = {$newAutoIncrement}");

                $this->info("✅ Đã sắp xếp lại thành công! ID hiện tại từ 1 đến {$maxId}");
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
            'inventories' => 'book_id',
            'borrows' => 'book_id',
            'borrow_items' => 'book_id',
            'reviews' => 'book_id',
            'reservations' => 'book_id',
            'favorites' => 'book_id',
            'book_items' => 'book_id',
            'stock_movements' => 'book_id',
            'inventory_receipts' => 'book_id',
            'display_allocations' => 'book_id',
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
                            ->update([$column => $newId + 1000000]);
                    }
                }
                
                // Then update to final value
                foreach ($idMapping as $oldId => $newId) {
                    if ($oldId != $newId) {
                        DB::table($table)
                            ->where($column, $newId + 1000000)
                            ->update([$column => $newId]);
                    }
                }
            }
        }
    }
}

