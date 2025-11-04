<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;

class NotificationTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Nhắc nhở trả sách sắp đến hạn',
                'type' => 'borrow_reminder',
                'channel' => 'email',
                'subject' => 'Nhắc nhở trả sách - {{book_title}}',
                'content' => "Xin chào {{reader_name}},\n\nSách '{{book_title}}' của bạn sắp đến hạn trả vào ngày {{due_date}}.\nCòn {{days_remaining}} ngày nữa.\n\nVui lòng trả sách đúng hạn để tránh phí phạt.\n\nTrân trọng,\nThư viện",
                'variables' => ['reader_name', 'book_title', 'due_date', 'days_remaining'],
                'is_active' => true,
            ],
            [
                'name' => 'Cảnh báo sách quá hạn',
                'type' => 'overdue_notification',
                'channel' => 'email',
                'subject' => 'Cảnh báo: Sách quá hạn - {{book_title}}',
                'content' => "Xin chào {{reader_name}},\n\nSách '{{book_title}}' của bạn đã quá hạn trả {{days_overdue}} ngày.\nHạn trả: {{due_date}}\n\nVui lòng trả sách ngay để tránh phí phạt tăng cao.\n\nTrân trọng,\nThư viện",
                'variables' => ['reader_name', 'book_title', 'due_date', 'days_overdue'],
                'is_active' => true,
            ],
            [
                'name' => 'Thông báo sách đặt trước sẵn sàng',
                'type' => 'reservation_ready',
                'channel' => 'email',
                'subject' => 'Sách đặt trước sẵn sàng - {{book_title}}',
                'content' => "Xin chào {{reader_name}},\n\nSách '{{book_title}}' mà bạn đã đặt trước đã sẵn sàng để nhận.\nNgày sẵn sàng: {{ready_date}}\nHạn nhận: {{expiry_date}}\n\nVui lòng đến thư viện để nhận sách trong thời gian quy định.\n\nTrân trọng,\nThư viện",
                'variables' => ['reader_name', 'book_title', 'ready_date', 'expiry_date'],
                'is_active' => true,
            ],
            [
                'name' => 'Thông báo phạt',
                'type' => 'fine_notification',
                'channel' => 'email',
                'subject' => 'Thông báo phạt - {{fine_type}}',
                'content' => "Xin chào {{reader_name}},\n\nBạn có phạt {{fine_type}} cho sách '{{book_title}}'.\nSố tiền phạt: {{fine_amount}}\nHạn thanh toán: {{due_date}}\n\nVui lòng thanh toán phạt để tiếp tục sử dụng dịch vụ thư viện.\n\nTrân trọng,\nThư viện",
                'variables' => ['reader_name', 'book_title', 'fine_amount', 'due_date', 'fine_type'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::firstOrCreate(
                ['type' => $template['type'], 'channel' => $template['channel']],
                $template
            );
        }
    }
}

