<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DocumentSeeder extends Seeder
{
    public function run()
    {
        $documents = [
            [
                'title' => 'Luật quản lý thuế và các văn bản hướng dẫn thi hành',
                'description' => 'Luật quản lý thuế số 78/2006/QH11 được Quốc hội nước Cộng hòa xã hội chủ nghĩa Việt Nam thông qua ngày 29 tháng 11 năm 2006, có hiệu lực từ ngày 01 tháng 7 năm 2007.',
                'published_date' => Carbon::parse('2022-10-06'),
                'link_url' => '#',
            ],
            [
                'title' => 'Nghị định 123/2020/NĐ-CP về hóa đơn điện tử',
                'description' => 'Nghị định quy định về hóa đơn điện tử và các vấn đề liên quan đến việc sử dụng hóa đơn điện tử trong hoạt động kinh doanh.',
                'published_date' => Carbon::parse('2020-10-19'),
                'link_url' => '#',
            ],
            [
                'title' => 'Thông tư 78/2021/TT-BTC về kế toán',
                'description' => 'Thông tư hướng dẫn chế độ kế toán doanh nghiệp, áp dụng cho các doanh nghiệp thuộc mọi lĩnh vực, mọi thành phần kinh tế.',
                'published_date' => Carbon::parse('2021-09-14'),
                'link_url' => '#',
            ],
            [
                'title' => 'Luật Doanh nghiệp 2020',
                'description' => 'Luật Doanh nghiệp số 59/2020/QH14 quy định về việc thành lập, quản lý, tổ chức lại, giải thể và hoạt động có liên quan của doanh nghiệp.',
                'published_date' => Carbon::parse('2020-06-17'),
                'link_url' => '#',
            ],
            [
                'title' => 'Luật Xây dựng 2014',
                'description' => 'Luật Xây dựng số 50/2014/QH13 quy định về hoạt động đầu tư xây dựng; quyền và nghĩa vụ của các cơ quan, tổ chức, cá nhân tham gia hoạt động đầu tư xây dựng.',
                'published_date' => Carbon::parse('2014-06-18'),
                'link_url' => '#',
            ],
            [
                'title' => 'Quy chuẩn kỹ thuật quốc gia về an toàn lao động trong xây dựng',
                'description' => 'Quy chuẩn quy định các yêu cầu về an toàn lao động trong thi công xây dựng công trình, áp dụng cho tất cả các hoạt động xây dựng.',
                'published_date' => Carbon::parse('2021-03-15'),
                'link_url' => '#',
            ],
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
