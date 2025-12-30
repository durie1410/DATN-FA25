<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowCartItem;
use App\Models\BorrowCart;

class CleanupInvalidCartItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:cleanup {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dọn dẹp các cart items không còn sách (book bị xóa)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Đang kiểm tra các cart items không hợp lệ...');
        $this->newLine();

        // Đếm số lượng items không hợp lệ
        $invalidCount = BorrowCartItem::whereDoesntHave('book')->count();

        if ($invalidCount === 0) {
            $this->info('✅ Không có cart items không hợp lệ nào!');
            return 0;
        }

        $this->warn("⚠️  Tìm thấy {$invalidCount} cart items không còn sách.");
        
        // Hiển thị danh sách
        $this->info('📋 Danh sách:');
        $invalidItems = BorrowCartItem::whereDoesntHave('book')->get();
        
        $tableData = [];
        foreach ($invalidItems as $item) {
            $tableData[] = [
                'ID' => $item->id,
                'Cart ID' => $item->cart_id,
                'Book ID' => $item->book_id,
                'Quantity' => $item->quantity,
            ];
        }
        
        $this->table(['ID', 'Cart ID', 'Book ID', 'Quantity'], $tableData);
        $this->newLine();

        // Xác nhận trước khi xóa
        if (!$this->option('force')) {
            if (!$this->confirm('Bạn có muốn xóa các items này không?')) {
                $this->info('❌ Đã hủy. Không có thay đổi nào được thực hiện.');
                return 0;
            }
        }

        // Xóa các items không hợp lệ
        $this->info('🗑️  Đang xóa các cart items không hợp lệ...');
        $deletedCount = BorrowCartItem::whereDoesntHave('book')->delete();
        $this->info("✅ Đã xóa {$deletedCount} cart items không hợp lệ!");

        // Cập nhật lại total_items cho các carts
        $this->info('🔄 Đang cập nhật lại tổng items cho các giỏ sách...');
        $carts = BorrowCart::all();
        foreach ($carts as $cart) {
            $cart->update(['total_items' => $cart->items()->count()]);
        }
        
        $this->newLine();
        $this->info('✅ Hoàn tất dọn dẹp!');
        
        return 0;
    }
}
