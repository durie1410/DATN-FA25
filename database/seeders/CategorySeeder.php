<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['ten_the_loai' => 'Tiểu thuyết'],
            ['ten_the_loai' => 'Khoa học'],
            ['ten_the_loai' => 'Lịch sử'],
            ['ten_the_loai' => 'Công nghệ'],
            ['ten_the_loai' => 'Kinh tế'],
            ['ten_the_loai' => 'Văn học'],
            ['ten_the_loai' => 'Giáo dục'],
            ['ten_the_loai' => 'Y học'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}