<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AiSearchController extends Controller
{
    /**
     * Gợi ý từ khóa tìm kiếm thông minh
     */
    public function suggest(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'suggestions' => []
            ]);
        }
        
        // Cache kết quả trong 5 phút
        $cacheKey = 'search_suggestions_' . md5($query);
        
        $suggestions = Cache::remember($cacheKey, 300, function () use ($query) {
            $results = [];
            
            // 1. Tìm kiếm sách theo tên
            $books = Book::where('ten_sach', 'LIKE', "%{$query}%")
                ->where('trang_thai', 'active')
                ->limit(5)
                ->get(['id', 'ten_sach', 'tac_gia', 'hinh_anh']);
            
            foreach ($books as $book) {
                $results[] = [
                    'type' => 'book',
                    'id' => $book->id,
                    'title' => $book->ten_sach,
                    'subtitle' => $book->tac_gia,
                    'image' => $book->hinh_anh ? asset('storage/' . $book->hinh_anh) : null,
                    'url' => route('books.show', $book->id)
                ];
            }
            
            // 2. Tìm kiếm theo tác giả
            $authors = Book::where('tac_gia', 'LIKE', "%{$query}%")
                ->where('trang_thai', 'active')
                ->select('tac_gia')
                ->distinct()
                ->limit(3)
                ->get();
            
            foreach ($authors as $author) {
                $results[] = [
                    'type' => 'author',
                    'title' => $author->tac_gia,
                    'subtitle' => 'Tác giả',
                    'url' => route('books.public', ['keyword' => $author->tac_gia])
                ];
            }
            
            // 3. Tìm kiếm theo thể loại
            $categories = Category::where('ten_the_loai', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get(['id', 'ten_the_loai']);
            
            foreach ($categories as $category) {
                $results[] = [
                    'type' => 'category',
                    'id' => $category->id,
                    'title' => $category->ten_the_loai,
                    'subtitle' => 'Thể loại',
                    'url' => route('books.public', ['category_id' => $category->id])
                ];
            }
            
            // 4. Gợi ý từ khóa phổ biến liên quan
            $popularKeywords = $this->getPopularKeywords($query);
            foreach ($popularKeywords as $keyword) {
                $results[] = [
                    'type' => 'keyword',
                    'title' => $keyword,
                    'subtitle' => 'Từ khóa phổ biến',
                    'url' => route('books.public', ['keyword' => $keyword])
                ];
            }
            
            return array_slice($results, 0, 10);
        });
        
        return response()->json([
            'suggestions' => $suggestions
        ]);
    }
    
    /**
     * Lấy từ khóa phổ biến liên quan
     */
    private function getPopularKeywords($query)
    {
        // Danh sách từ khóa phổ biến được định nghĩa trước
        $popularKeywords = [
            'văn học' => ['văn học việt nam', 'văn học nước ngoài', 'văn học hiện đại', 'văn học cổ điển'],
            'kinh tế' => ['kinh tế học', 'quản trị kinh doanh', 'marketing', 'tài chính'],
            'lịch sử' => ['lịch sử việt nam', 'lịch sử thế giới', 'lịch sử cổ đại', 'lịch sử hiện đại'],
            'khoa học' => ['khoa học tự nhiên', 'khoa học xã hội', 'công nghệ', 'vật lý'],
            'thiếu nhi' => ['truyện thiếu nhi', 'sách cho bé', 'truyện tranh', 'sách tô màu'],
            'kỹ năng' => ['kỹ năng sống', 'kỹ năng mềm', 'phát triển bản thân', 'tư duy'],
            'tiểu thuyết' => ['tiểu thuyết trinh thám', 'tiểu thuyết lãng mạn', 'tiểu thuyết kinh dị', 'tiểu thuyết phiêu lưu'],
        ];
        
        $results = [];
        $queryLower = mb_strtolower($query);
        
        foreach ($popularKeywords as $key => $keywords) {
            if (mb_strpos($queryLower, $key) !== false) {
                $results = array_merge($results, $keywords);
            }
        }
        
        // Nếu không tìm thấy, trả về một số từ khóa chung
        if (empty($results)) {
            $results = ['sách hay', 'sách mới', 'sách bán chạy'];
        }
        
        return array_slice($results, 0, 3);
    }
    
    /**
     * Tìm kiếm nâng cao với AI
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $filters = $request->except(['q', 'page']);
        
        // Sử dụng AdvancedSearchService nếu có
        if (class_exists('\App\Services\AdvancedSearchService')) {
            $searchService = app(\App\Services\AdvancedSearchService::class);
            $results = $searchService->searchBooks($query, $filters);
        } else {
            // Fallback to basic search
            $results = Book::where('ten_sach', 'LIKE', "%{$query}%")
                ->orWhere('tac_gia', 'LIKE', "%{$query}%")
                ->orWhere('mo_ta', 'LIKE', "%{$query}%")
                ->where('trang_thai', 'active')
                ->paginate(20);
        }
        
        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}

