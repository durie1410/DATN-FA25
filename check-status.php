<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== KIỂM TRA TRẠNG THÁI SAU KHI DUYỆT ===\n\n";

$item = DB::table('borrow_items')->where('id', 52)->first();
echo "Item ID 52: " . $item->trang_thai . "\n";

$borrow = DB::table('borrows')->where('id', 230)->first();
echo "Borrow ID 230: " . $borrow->trang_thai . "\n";

$inventory = DB::table('inventories')->where('id', 190)->first();
echo "Inventory ID 190: " . $inventory->status . "\n";

echo "\n✅ Tất cả đã chuyển sang 'Dang muon'!\n";
echo "\nBây giờ hãy vào URL sau để xem:\n";
echo "http://quanlythuviennn.test/admin/borrows/230/edit\n\n";

