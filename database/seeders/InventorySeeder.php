<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Inventory;
use App\Models\User;
use Faker\Factory as Faker;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('vi_VN');
        
        // Lấy admin user đầu tiên để làm created_by
        $adminUser = User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = User::first();
        }
        
        if (!$adminUser) {
            $this->command->error('Không tìm thấy user nào để tạo inventory!');
            return;
        }

        $this->command->info('Bắt đầu tạo dữ liệu inventory...');
        
        // Lấy tất cả sách
        $books = Book::all();
        $totalBooks = $books->count();
        
        $this->command->info("Tìm thấy {$totalBooks} sách để tạo inventory");
        
        foreach ($books as $index => $book) {
            // Tạo 1-3 bản copy cho mỗi sách
            $copiesCount = $faker->numberBetween(1, 3);
            
            for ($i = 1; $i <= $copiesCount; $i++) {
                // Tạo mã vạch duy nhất
                $barcode = 'BK' . str_pad($book->id, 4, '0', STR_PAD_LEFT) . str_pad($i, 2, '0', STR_PAD_LEFT);
                
                // Tạo vị trí trong kho
                $shelf = $faker->numberBetween(1, 10);
                $floor = $faker->numberBetween(1, 5);
                $position = $faker->numberBetween(1, 20);
                $location = "Kệ {$shelf}, Tầng {$floor}, Vị trí {$position}";
                
                // Tình trạng sách
                $conditions = ['Moi', 'Tot', 'Trung binh', 'Cu'];
                $condition = $faker->randomElement($conditions);
                
                // Trạng thái sách (80% có sẵn, 20% đang mượn)
                $statuses = ['Co san', 'Co san', 'Co san', 'Co san', 'Dang muon']; // 80% có sẵn
                $status = $faker->randomElement($statuses);
                
                // Giá mua (từ 50k đến 200k)
                $purchasePrice = $faker->numberBetween(50000, 200000);
                
                // Ngày mua (trong vòng 2 năm qua)
                $purchaseDate = $faker->dateTimeBetween('-2 years', 'now');
                
                // Ghi chú
                $notes = $faker->optional(0.3)->sentence();
                
                try {
                    Inventory::create([
                        'book_id' => $book->id,
                        'barcode' => $barcode,
                        'location' => $location,
                        'condition' => $condition,
                        'status' => $status,
                        'purchase_price' => $purchasePrice,
                        'purchase_date' => $purchaseDate,
                        'notes' => $notes,
                        'created_by' => $adminUser->id,
                    ]);
                } catch (\Exception $e) {
                    $this->command->error("Lỗi khi tạo inventory cho sách {$book->id}: " . $e->getMessage());
                }
            }
            
            // Hiển thị tiến trình
            if (($index + 1) % 10 == 0) {
                $this->command->info("Đã xử lý " . ($index + 1) . "/{$totalBooks} sách");
            }
        }
        
        $totalInventories = Inventory::count();
        $this->command->info("Hoàn thành! Đã tạo tổng cộng {$totalInventories} inventory records");
        
        // Hiển thị thống kê
        $availableCount = Inventory::where('status', 'Co san')->count();
        $borrowedCount = Inventory::where('status', 'Dang muon')->count();
        
        $this->command->info("Thống kê:");
        $this->command->info("- Có sẵn: {$availableCount}");
        $this->command->info("- Đang mượn: {$borrowedCount}");
    }
}

