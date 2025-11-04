<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailSubscriber;
use App\Models\EmailCampaign;
use App\Models\User;
use Carbon\Carbon;

class EmailMarketingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo subscribers mẫu
        $subscribers = [
            [
                'email' => 'nguyenvanan@example.com',
                'name' => 'Nguyễn Văn An',
                'status' => 'active',
                'tags' => ['student', 'technology', 'programming'],
                'preferences' => ['weekly_newsletter' => true, 'book_recommendations' => true],
                'subscribed_at' => Carbon::now()->subDays(30),
                'source' => 'website',
            ],
            [
                'email' => 'tranthibinh@example.com',
                'name' => 'Trần Thị Bình',
                'status' => 'active',
                'tags' => ['teacher', 'education', 'literature'],
                'preferences' => ['monthly_newsletter' => true, 'event_notifications' => true],
                'subscribed_at' => Carbon::now()->subDays(15),
                'source' => 'facebook',
            ],
            [
                'email' => 'levanminh@example.com',
                'name' => 'Lê Văn Minh',
                'status' => 'active',
                'tags' => ['researcher', 'science', 'academic'],
                'preferences' => ['research_updates' => true, 'conference_announcements' => true],
                'subscribed_at' => Carbon::now()->subDays(7),
                'source' => 'email_invitation',
            ],
            [
                'email' => 'phamthithu@example.com',
                'name' => 'Phạm Thị Thu',
                'status' => 'active',
                'tags' => ['student', 'art', 'design'],
                'preferences' => ['art_exhibitions' => true, 'creative_workshops' => true],
                'subscribed_at' => Carbon::now()->subDays(3),
                'source' => 'website',
            ],
            [
                'email' => 'hoangvanhung@example.com',
                'name' => 'Hoàng Văn Hùng',
                'status' => 'unsubscribed',
                'tags' => ['business', 'management'],
                'preferences' => ['business_news' => false],
                'subscribed_at' => Carbon::now()->subDays(60),
                'unsubscribed_at' => Carbon::now()->subDays(5),
                'source' => 'linkedin',
            ],
        ];

        foreach ($subscribers as $subscriberData) {
            EmailSubscriber::create($subscriberData);
        }

        // Tạo campaigns mẫu
        $adminUser = User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = User::first();
        }

        $campaigns = [
            [
                'name' => 'Chào mừng năm học mới 2024',
                'subject' => '🎓 Chào mừng năm học mới - Khám phá thư viện số',
                'content' => "Xin chào {{name}},\n\nNăm học mới đã bắt đầu! Thư viện chúng tôi rất vui được chào đón bạn với nhiều dịch vụ và tài liệu mới.\n\n📚 Hơn 10,000 đầu sách mới\n🖥️ Hệ thống tìm kiếm thông minh\n📱 App thư viện di động\n🎯 Dịch vụ tư vấn học tập\n\nHãy đến thư viện để khám phá những điều thú vị!\n\nTrân trọng,\nThư viện",
                'template' => 'marketing',
                'target_criteria' => [
                    'tags' => ['student', 'teacher'],
                    'subscribed_after' => Carbon::now()->subDays(30)->toDateString(),
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subDays(10),
                'total_recipients' => 150,
                'sent_count' => 150,
                'opened_count' => 89,
                'clicked_count' => 23,
                'metadata' => [
                    'include_featured_books' => true,
                    'include_stats' => true,
                    'action_url' => url('/books'),
                    'action_text' => 'Khám phá sách mới',
                ],
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Thông báo sách mới tháng 10',
                'subject' => '📖 Sách mới tháng 10 - Những tác phẩm hay không thể bỏ qua',
                'content' => "Xin chào {{name}},\n\nTháng 10 này, thư viện đã bổ sung thêm nhiều đầu sách mới thú vị:\n\n🔬 Sách khoa học công nghệ\n📚 Tiểu thuyết văn học\n🎨 Sách nghệ thuật và thiết kế\n📖 Sách giáo dục và nghiên cứu\n\nĐặc biệt, chúng tôi có chương trình giảm giá 20% cho việc mượn sách trong tuần này!\n\nHãy đến thư viện để tìm hiểu thêm!\n\nTrân trọng,\nThư viện",
                'template' => 'marketing',
                'target_criteria' => [
                    'tags' => ['student', 'teacher', 'researcher'],
                ],
                'status' => 'scheduled',
                'scheduled_at' => Carbon::now()->addDays(2),
                'total_recipients' => 200,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'metadata' => [
                    'include_featured_books' => true,
                    'include_stats' => false,
                    'action_url' => url('/books/new'),
                    'action_text' => 'Xem sách mới',
                ],
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Nhắc nhở trả sách quá hạn',
                'subject' => '⚠️ Nhắc nhở: Sách mượn sắp đến hạn trả',
                'content' => "Xin chào {{name}},\n\nChúng tôi muốn nhắc nhở bạn về việc trả sách đúng hạn.\n\n📅 Kiểm tra lịch mượn sách của bạn\n💰 Tránh phí phạt do trả muộn\n🔄 Gia hạn mượn sách nếu cần\n\nVui lòng trả sách đúng hạn để duy trì quyền mượn sách.\n\nTrân trọng,\nThư viện",
                'template' => 'simple',
                'target_criteria' => [
                    'tags' => ['student', 'teacher'],
                ],
                'status' => 'draft',
                'total_recipients' => 0,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'metadata' => [
                    'include_featured_books' => false,
                    'include_stats' => false,
                    'action_url' => url('/reader/borrows'),
                    'action_text' => 'Xem lịch mượn sách',
                ],
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($campaigns as $campaignData) {
            EmailCampaign::create($campaignData);
        }

        $this->command->info('Email marketing data seeded successfully!');
    }
}