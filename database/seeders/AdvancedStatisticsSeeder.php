<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportTemplate;
use App\Models\SearchLog;
use App\Models\NotificationLog;
use App\Models\InventoryTransaction;
use App\Models\User;
use App\Models\Book;
use App\Models\Reader;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Reservation;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AdvancedStatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        // Tạo các template báo cáo mẫu
        $this->createReportTemplates();
        
        // Tạo dữ liệu log tìm kiếm
        $this->createSearchLogs($faker);
        
        // Tạo dữ liệu log thông báo
        $this->createNotificationLogs($faker);
        
        // Tạo dữ liệu giao dịch kho
        $this->createInventoryTransactions($faker);
    }

    private function createReportTemplates()
    {
        $templates = [
            [
                'name' => 'Báo cáo mượn sách theo tháng',
                'type' => 'borrows',
                'description' => 'Thống kê chi tiết về việc mượn sách theo từng tháng',
                'columns' => [
                    ['key' => 'reader_name', 'label' => 'Tên độc giả', 'type' => 'text'],
                    ['key' => 'book_title', 'label' => 'Tên sách', 'type' => 'text'],
                    ['key' => 'borrow_date', 'label' => 'Ngày mượn', 'type' => 'date'],
                    ['key' => 'return_date', 'label' => 'Ngày trả', 'type' => 'date'],
                    ['key' => 'status', 'label' => 'Trạng thái', 'type' => 'text'],
                ],
                'filters' => [
                    ['key' => 'from_date', 'label' => 'Từ ngày', 'type' => 'date'],
                    ['key' => 'to_date', 'label' => 'Đến ngày', 'type' => 'date'],
                    ['key' => 'status', 'label' => 'Trạng thái', 'type' => 'select', 'options' => ['Dang muon', 'Da tra', 'Qua han']],
                ],
                'group_by' => ['ngay_muon'],
                'order_by' => [['column' => 'ngay_muon', 'direction' => 'desc']],
                'is_active' => true,
                'is_public' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Thống kê độc giả tích cực',
                'type' => 'readers',
                'description' => 'Danh sách độc giả có hoạt động mượn sách cao',
                'columns' => [
                    ['key' => 'ho_ten', 'label' => 'Họ tên', 'type' => 'text'],
                    ['key' => 'email', 'label' => 'Email', 'type' => 'email'],
                    ['key' => 'so_the_doc_gia', 'label' => 'Số thẻ', 'type' => 'text'],
                    ['key' => 'total_borrows', 'label' => 'Tổng lượt mượn', 'type' => 'number'],
                    ['key' => 'active_borrows', 'label' => 'Đang mượn', 'type' => 'number'],
                    ['key' => 'total_fines', 'label' => 'Tổng phạt', 'type' => 'currency'],
                ],
                'filters' => [
                    ['key' => 'faculty_id', 'label' => 'Khoa', 'type' => 'select'],
                    ['key' => 'department_id', 'label' => 'Bộ môn', 'type' => 'select'],
                    ['key' => 'min_borrows', 'label' => 'Tối thiểu lượt mượn', 'type' => 'number'],
                ],
                'group_by' => ['faculty_id'],
                'order_by' => [['column' => 'total_borrows', 'direction' => 'desc']],
                'is_active' => true,
                'is_public' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Báo cáo sách phổ biến',
                'type' => 'books',
                'description' => 'Thống kê sách được mượn nhiều nhất',
                'columns' => [
                    ['key' => 'ten_sach', 'label' => 'Tên sách', 'type' => 'text'],
                    ['key' => 'tac_gia', 'label' => 'Tác giả', 'type' => 'text'],
                    ['key' => 'category_name', 'label' => 'Thể loại', 'type' => 'text'],
                    ['key' => 'borrow_count', 'label' => 'Lượt mượn', 'type' => 'number'],
                    ['key' => 'average_rating', 'label' => 'Đánh giá TB', 'type' => 'number'],
                    ['key' => 'views_count', 'label' => 'Lượt xem', 'type' => 'number'],
                ],
                'filters' => [
                    ['key' => 'category_id', 'label' => 'Thể loại', 'type' => 'select'],
                    ['key' => 'year', 'label' => 'Năm xuất bản', 'type' => 'number'],
                    ['key' => 'min_borrows', 'label' => 'Tối thiểu lượt mượn', 'type' => 'number'],
                ],
                'group_by' => ['category_id'],
                'order_by' => [['column' => 'borrow_count', 'direction' => 'desc']],
                'is_active' => true,
                'is_public' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Báo cáo phạt và vi phạm',
                'type' => 'fines',
                'description' => 'Thống kê chi tiết về các khoản phạt và vi phạm',
                'columns' => [
                    ['key' => 'reader_name', 'label' => 'Tên độc giả', 'type' => 'text'],
                    ['key' => 'book_title', 'label' => 'Sách vi phạm', 'type' => 'text'],
                    ['key' => 'amount', 'label' => 'Số tiền phạt', 'type' => 'currency'],
                    ['key' => 'type', 'label' => 'Loại phạt', 'type' => 'text'],
                    ['key' => 'status', 'label' => 'Trạng thái', 'type' => 'text'],
                    ['key' => 'created_at', 'label' => 'Ngày tạo', 'type' => 'date'],
                ],
                'filters' => [
                    ['key' => 'from_date', 'label' => 'Từ ngày', 'type' => 'date'],
                    ['key' => 'to_date', 'label' => 'Đến ngày', 'type' => 'date'],
                    ['key' => 'status', 'label' => 'Trạng thái', 'type' => 'select', 'options' => ['pending', 'paid', 'cancelled']],
                    ['key' => 'type', 'label' => 'Loại phạt', 'type' => 'select', 'options' => ['overdue', 'damage', 'loss']],
                ],
                'group_by' => ['type', 'status'],
                'order_by' => [['column' => 'amount', 'direction' => 'desc']],
                'is_active' => true,
                'is_public' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Thống kê tổng quan hệ thống',
                'type' => 'statistics',
                'description' => 'Báo cáo tổng quan về hoạt động của thư viện',
                'columns' => [
                    ['key' => 'metric', 'label' => 'Chỉ số', 'type' => 'text'],
                    ['key' => 'value', 'label' => 'Giá trị', 'type' => 'number'],
                    ['key' => 'change', 'label' => 'Thay đổi', 'type' => 'percentage'],
                    ['key' => 'trend', 'label' => 'Xu hướng', 'type' => 'text'],
                ],
                'filters' => [
                    ['key' => 'from_date', 'label' => 'Từ ngày', 'type' => 'date'],
                    ['key' => 'to_date', 'label' => 'Đến ngày', 'type' => 'date'],
                    ['key' => 'period', 'label' => 'Chu kỳ', 'type' => 'select', 'options' => ['daily', 'weekly', 'monthly', 'yearly']],
                ],
                'group_by' => null,
                'order_by' => null,
                'is_active' => true,
                'is_public' => true,
                'created_by' => 1,
            ],
        ];

        foreach ($templates as $template) {
            ReportTemplate::create($template);
        }
    }

    private function createSearchLogs($faker)
    {
        $searchTypes = ['books', 'readers', 'borrows', 'fines'];
        $queries = [
            'sách', 'tiểu thuyết', 'khoa học', 'lịch sử', 'toán học', 'vật lý', 'hóa học',
            'sinh học', 'văn học', 'triết học', 'kinh tế', 'chính trị', 'công nghệ',
            'Nguyễn Văn A', 'Trần Thị B', 'Lê Văn C', 'Phạm Thị D', 'Hoàng Văn E',
            'mượn sách', 'trả sách', 'gia hạn', 'phạt', 'quá hạn', 'đặt trước'
        ];

        // Lấy danh sách user IDs có sẵn
        $userIds = \App\Models\User::pluck('id')->toArray();
        
        for ($i = 0; $i < 500; $i++) {
            SearchLog::create([
                'query' => $faker->randomElement($queries),
                'type' => $faker->randomElement($searchTypes),
                'filters' => $faker->optional(0.3)->randomElements(['category', 'year', 'status'], $faker->numberBetween(1, 3)),
                'results_count' => $faker->numberBetween(0, 50),
                'user_id' => $faker->optional(0.8)->randomElement($userIds),
                'ip_address' => $faker->ipv4(),
                'user_agent' => $faker->userAgent(),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
            ]);
        }
    }

    private function createNotificationLogs($faker)
    {
        $notificationTypes = [
            'borrow_reminder', 'overdue_notice', 'reservation_ready', 
            'fine_notice', 'system_maintenance', 'new_book_available'
        ];

        $channels = ['email', 'sms', 'push', 'in_app'];
        $statuses = ['pending', 'sent', 'failed', 'delivered'];
        
        // Lấy danh sách template IDs có sẵn
        $templateIds = \App\Models\NotificationTemplate::pluck('id')->toArray();
        $bookIds = \App\Models\Book::pluck('id')->toArray();
        $borrowIds = \App\Models\Borrow::pluck('id')->toArray();

        for ($i = 0; $i < 300; $i++) {
            NotificationLog::create([
                'template_id' => $faker->optional(0.7)->randomElement($templateIds),
                'type' => $faker->randomElement($notificationTypes),
                'channel' => $faker->randomElement($channels),
                'recipient' => $faker->email(),
                'subject' => $faker->sentence(3),
                'content' => $faker->paragraph(2),
                'status' => $faker->randomElement($statuses),
                'error_message' => $faker->optional(0.1)->sentence(),
                'metadata' => json_encode([
                    'book_id' => $faker->optional(0.6)->randomElement($bookIds),
                    'borrow_id' => $faker->optional(0.4)->randomElement($borrowIds),
                    'fine_amount' => $faker->optional(0.3)->randomFloat(2, 10000, 100000),
                ]),
                'sent_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ]);
        }
    }

    private function createInventoryTransactions($faker)
    {
        $transactionTypes = ['Nhap kho', 'Xuat kho', 'Chuyen kho', 'Kiem ke', 'Thanh ly', 'Sua chua'];
        $conditions = ['Moi', 'Tot', 'Trung binh', 'Cu', 'Hong'];
        $statuses = ['Co san', 'Dang muon', 'Mat', 'Hong', 'Thanh ly'];
        
        // Lấy danh sách inventory IDs và user IDs có sẵn
        $inventoryIds = \App\Models\Inventory::pluck('id')->toArray();
        $userIds = \App\Models\User::pluck('id')->toArray();

        for ($i = 0; $i < 200; $i++) {
            InventoryTransaction::create([
                'inventory_id' => $faker->randomElement($inventoryIds),
                'type' => $faker->randomElement($transactionTypes),
                'quantity' => $faker->numberBetween(1, 10),
                'from_location' => $faker->optional(0.5)->randomElement(['Ke A', 'Ke B', 'Kho']),
                'to_location' => $faker->optional(0.5)->randomElement(['Ke A', 'Ke B', 'Kho']),
                'condition_before' => $faker->randomElement($conditions),
                'condition_after' => $faker->randomElement($conditions),
                'status_before' => $faker->randomElement($statuses),
                'status_after' => $faker->randomElement($statuses),
                'reason' => $faker->optional(0.7)->sentence(),
                'notes' => $faker->optional(0.5)->sentence(),
                'performed_by' => $faker->randomElement($userIds),
            ]);
        }
    }
}
