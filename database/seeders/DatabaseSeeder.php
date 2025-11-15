<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Seeders cơ bản
            CategorySeeder::class,
            PublisherSeeder::class,
            
            // Seeder quản lý người dùng (bao gồm Faculty, Department, Users)
            UserManagementSeeder::class,
            
            // Seeders phụ thuộc
            BookSeeder::class,
            DocumentSeeder::class,
            MotivationalBooksSeeder::class,
            InventorySeeder::class,
            PurchasableBookSeeder::class,
            SampleDataSeeder::class,
            ReservationSeeder::class,
            RolePermissionSeeder::class,
            NotificationTemplateSeeder::class,
            
            // Seeders thống kê nâng cao
            AdvancedDataSeeder::class,
            AdvancedStatisticsSeeder::class,
            LibraryPricingSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
