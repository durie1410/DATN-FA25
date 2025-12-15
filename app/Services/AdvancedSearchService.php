<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Reader;
use App\Models\Borrow;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdvancedSearchService
{
    /**
     * Tìm kiếm sách nâng cao
     */
    public function searchBooks($query, $filters = [])
    {
        $searchQuery = Book::with(['category', 'reviews', 'borrows']);

        // Tìm kiếm full-text trong nhiều trường
        if ($query) {
            $searchQuery->where(function($q) use ($query) {
                $q->where('ten_sach', 'like', "%{$query}%")
                  ->orWhere('tac_gia', 'like', "%{$query}%")
                  ->orWhere('mo_ta', 'like', "%{$query}%")
                  ->orWhereHas('category', function($catQuery) use ($query) {
                      $catQuery->where('ten_the_loai', 'like', "%{$query}%");
                  });
            });
        }

        // Áp dụng filters
        if (isset($filters['category_id']) && $filters['category_id']) {
            $searchQuery->where('category_id', $filters['category_id']);
        }

        if (isset($filters['year_from']) && $filters['year_from']) {
            $searchQuery->where('nam_xuat_ban', '>=', $filters['year_from']);
        }

        if (isset($filters['year_to']) && $filters['year_to']) {
            $searchQuery->where('nam_xuat_ban', '<=', $filters['year_to']);
        }

        if (isset($filters['available_only']) && $filters['available_only']) {
            $searchQuery->whereDoesntHave('borrows', function($borrowQuery) {
                $borrowQuery->where('trang_thai', 'Dang muon');
            });
        }

        if (isset($filters['min_rating']) && $filters['min_rating']) {
            $searchQuery->whereHas('reviews', function($reviewQuery) use ($filters) {
                $reviewQuery->where('rating', '>=', $filters['min_rating'])
                           ->where('is_verified', true);
            });
        }

        // Sắp xếp kết quả
        $sortBy = $filters['sort_by'] ?? 'relevance';
        switch ($sortBy) {
            case 'title':
                $searchQuery->orderBy('ten_sach');
                break;
            case 'author':
                $searchQuery->orderBy('tac_gia');
                break;
            case 'year':
                $searchQuery->orderBy('nam_xuat_ban', 'asc');
                break;
            case 'rating':
                $searchQuery->leftJoin('reviews', 'books.id', '=', 'reviews.book_id')
                           ->where('reviews.is_verified', true)
                           ->selectRaw('books.*, AVG(reviews.rating) as avg_rating')
                           ->groupBy('books.id')
                           ->orderBy('avg_rating', 'asc');
                break;
            case 'popularity':
                $searchQuery->leftJoin('borrows', 'books.id', '=', 'borrows.book_id')
                           ->selectRaw('books.*, COUNT(borrows.id) as borrow_count')
                           ->groupBy('books.id')
                           ->orderBy('borrow_count', 'asc');
                break;
            default: // relevance
                if ($query) {
                    $searchQuery->orderByRaw("
                        CASE 
                            WHEN ten_sach LIKE ? THEN 1
                            WHEN tac_gia LIKE ? THEN 2
                            WHEN mo_ta LIKE ? THEN 3
                            ELSE 4
                        END
                    ", ["%{$query}%", "%{$query}%", "%{$query}%"]);
                }
                $searchQuery->orderBy('created_at', 'asc');
                break;
        }

        $results = $searchQuery->paginate($filters['per_page'] ?? 20);

        // Log tìm kiếm
        $this->logSearch('books', $query, $filters, $results->total());

        return $results;
    }

    /**
     * Tìm kiếm độc giả nâng cao
     */
    public function searchReaders($query, $filters = [])
    {
        $searchQuery = Reader::with(['borrows', 'fines']);

        if ($query) {
            $searchQuery->where(function($q) use ($query) {
                $q->where('ho_ten', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$query}%")
                  ->orWhere('so_the_doc_gia', 'like', "%{$query}%")
                  ->orWhere('dia_chi', 'like', "%{$query}%");
            });
        }

        // Áp dụng filters
        if (isset($filters['status']) && $filters['status']) {
            $searchQuery->where('trang_thai', $filters['status']);
        }

        if (isset($filters['gender']) && $filters['gender']) {
            $searchQuery->where('gioi_tinh', $filters['gender']);
        }

        if (isset($filters['age_from']) && $filters['age_from']) {
            $searchQuery->whereRaw('YEAR(CURDATE()) - YEAR(ngay_sinh) >= ?', [$filters['age_from']]);
        }

        if (isset($filters['age_to']) && $filters['age_to']) {
            $searchQuery->whereRaw('YEAR(CURDATE()) - YEAR(ngay_sinh) <= ?', [$filters['age_to']]);
        }

        if (isset($filters['has_active_borrows']) && $filters['has_active_borrows']) {
            $searchQuery->whereHas('borrows', function($borrowQuery) {
                $borrowQuery->where('trang_thai', 'Dang muon');
            });
        }

        if (isset($filters['has_pending_fines']) && $filters['has_pending_fines']) {
            $searchQuery->whereHas('fines', function($fineQuery) {
                $fineQuery->where('status', 'pending');
            });
        }

        // Sắp xếp
        $sortBy = $filters['sort_by'] ?? 'name';
        switch ($sortBy) {
            case 'name':
                $searchQuery->orderBy('ho_ten');
                break;
            case 'email':
                $searchQuery->orderBy('email');
                break;
            case 'card_number':
                $searchQuery->orderBy('so_the_doc_gia');
                break;
            case 'registration_date':
                $searchQuery->orderBy('ngay_cap_the', 'asc');
                break;
            case 'expiry_date':
                $searchQuery->orderBy('ngay_het_han');
                break;
        }

        $results = $searchQuery->paginate($filters['per_page'] ?? 20);

        // Log tìm kiếm
        $this->logSearch('readers', $query, $filters, $results->total());

        return $results;
    }

    /**
     * Tìm kiếm mượn sách nâng cao
     */
    public function searchBorrows($query, $filters = [])
    {
        $searchQuery = Borrow::with(['book', 'reader', 'librarian']);

        if ($query) {
            $searchQuery->where(function($q) use ($query) {
                $q->whereHas('book', function($bookQuery) use ($query) {
                    $bookQuery->where('ten_sach', 'like', "%{$query}%")
                             ->orWhere('tac_gia', 'like', "%{$query}%");
                })
                ->orWhereHas('reader', function($readerQuery) use ($query) {
                    $readerQuery->where('ho_ten', 'like', "%{$query}%")
                               ->orWhere('email', 'like', "%{$query}%")
                               ->orWhere('so_the_doc_gia', 'like', "%{$query}%");
                });
            });
        }

        // Áp dụng filters
        if (isset($filters['status']) && $filters['status']) {
            $searchQuery->where('trang_thai', $filters['status']);
        }

        if (isset($filters['from_date']) && $filters['from_date']) {
            $searchQuery->where('ngay_muon', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date']) && $filters['to_date']) {
            $searchQuery->where('ngay_muon', '<=', $filters['to_date']);
        }

        if (isset($filters['overdue_only']) && $filters['overdue_only']) {
            $searchQuery->where('trang_thai', 'Dang muon')
                       ->where('ngay_hen_tra', '<', now()->toDateString());
        }

        if (isset($filters['reader_id']) && $filters['reader_id']) {
            $searchQuery->where('reader_id', $filters['reader_id']);
        }

        if (isset($filters['book_id']) && $filters['book_id']) {
            $searchQuery->where('book_id', $filters['book_id']);
        }

        // Sắp xếp
        $sortBy = $filters['sort_by'] ?? 'borrow_date';
        switch ($sortBy) {
            case 'borrow_date':
                $searchQuery->orderBy('ngay_muon', 'asc');
                break;
            case 'due_date':
                $searchQuery->orderBy('ngay_hen_tra');
                break;
            case 'return_date':
                $searchQuery->orderBy('ngay_tra_thuc_te', 'asc');
                break;
            case 'status':
                $searchQuery->orderBy('trang_thai');
                break;
        }

        $results = $searchQuery->paginate($filters['per_page'] ?? 20);

        // Log tìm kiếm
        $this->logSearch('borrows', $query, $filters, $results->total());

        return $results;
    }

    /**
     * Tìm kiếm gợi ý (Auto-complete)
     */
    public function getSuggestions($query, $type = 'books', $limit = 10)
    {
        $suggestions = [];

        switch ($type) {
            case 'books':
                $suggestions = Book::where('ten_sach', 'like', "%{$query}%")
                    ->orWhere('tac_gia', 'like', "%{$query}%")
                    ->select('ten_sach', 'tac_gia')
                    ->limit($limit)
                    ->get()
                    ->map(function($book) {
                        return [
                            'text' => $book->ten_sach,
                            'subtext' => $book->tac_gia,
                            'type' => 'book'
                        ];
                    });
                break;

            case 'readers':
                $suggestions = Reader::where('ho_ten', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('so_the_doc_gia', 'like', "%{$query}%")
                    ->select('ho_ten', 'email', 'so_the_doc_gia')
                    ->limit($limit)
                    ->get()
                    ->map(function($reader) {
                        return [
                            'text' => $reader->ho_ten,
                            'subtext' => $reader->email . ' - ' . $reader->so_the_doc_gia,
                            'type' => 'reader'
                        ];
                    });
                break;
        }

        return $suggestions;
    }

    /**
     * Lấy từ khóa tìm kiếm phổ biến
     */
    public function getPopularQueries($type = null, $limit = 10)
    {
        $query = SearchLog::popularQueries($limit);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    /**
     * Lấy thống kê tìm kiếm
     */
    public function getSearchStats($fromDate = null, $toDate = null)
    {
        $query = SearchLog::query();

        if ($fromDate && $toDate) {
            $query->byDateRange($fromDate, $toDate);
        }

        return [
            'total_searches' => $query->count(),
            'unique_queries' => $query->distinct('query')->count(),
            'searches_by_type' => $query->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'popular_queries' => $query->popularQueries(10)->get(),
            'searches_by_day' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];
    }

    /**
     * Log tìm kiếm
     */
    private function logSearch($type, $query, $filters, $resultsCount)
    {
        SearchLog::create([
            'query' => $query,
            'type' => $type,
            'filters' => $filters,
            'results_count' => $resultsCount,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Tìm kiếm toàn cục (Global search)
     */
    public function globalSearch($query, $limit = 20)
    {
        $results = [];

        // Tìm kiếm sách
        $books = Book::where('ten_sach', 'like', "%{$query}%")
            ->orWhere('tac_gia', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function($book) {
                return [
                    'type' => 'book',
                    'title' => $book->ten_sach,
                    'subtitle' => $book->tac_gia,
                    'url' => route('admin.books.show', $book->id),
                    'icon' => 'fas fa-book'
                ];
            });

        // Tìm kiếm độc giả
        $readers = Reader::where('ho_ten', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('so_the_doc_gia', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function($reader) {
                return [
                    'type' => 'reader',
                    'title' => $reader->ho_ten,
                    'subtitle' => $reader->email,
                    'url' => route('admin.readers.show', $reader->id),
                    'icon' => 'fas fa-user'
                ];
            });

        // Tìm kiếm mượn sách
        $borrows = Borrow::whereHas('book', function($bookQuery) use ($query) {
                $bookQuery->where('ten_sach', 'like', "%{$query}%");
            })
            ->orWhereHas('reader', function($readerQuery) use ($query) {
                $readerQuery->where('ho_ten', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function($borrow) {
                return [
                    'type' => 'borrow',
                    'title' => $borrow->book->ten_sach,
                    'subtitle' => 'Mượn bởi: ' . $borrow->reader->ho_ten,
                    'url' => route('admin.borrows.show', $borrow->id),
                    'icon' => 'fas fa-exchange-alt'
                ];
            });

        $results = $books->concat($readers)->concat($borrows)->take($limit);

        // Log tìm kiếm toàn cục
        $this->logSearch('global', $query, [], $results->count());

        return $results;
    }
}



