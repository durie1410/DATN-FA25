<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriesExport;

class CategoryController extends Controller
{
    // Public method for frontend
    public function publicIndex(Request $request)
    {
        $categories = Category::withCount('books')
            ->orderBy('ten_the_loai', 'asc')
            ->get();

        return view('categories.public', compact('categories'));
    }

    // Hiển thị danh sách với tìm kiếm và lọc
    public function index(Request $request)
    {
        $query = Category::withCount('books');

        // Tìm kiếm theo tên thể loại
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('ten_the_loai', 'like', "%{$keyword}%");
        }

        // Lọc theo số lượng sách
        if ($request->filled('min_books')) {
            $query->having('books_count', '>=', $request->min_books);
        }
        if ($request->filled('max_books')) {
            $query->having('books_count', '<=', $request->max_books);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'name':
                $query->orderBy('ten_the_loai', $sortOrder);
                break;
            case 'books_count':
                $query->orderBy('books_count', $sortOrder);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $categories = $query->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    // Form thêm
    public function create()
    {
        return view('admin.categories.create');
    }

    // Lưu thể loại mới
    public function store(Request $request)
    {
        $request->validate([
            'ten_the_loai' => 'required|max:255|unique:categories',
            'mo_ta' => 'nullable|max:500',
            'trang_thai' => 'required|in:active,inactive',
            'mau_sac' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        Category::create([
            'ten_the_loai' => $request->ten_the_loai,
            'mo_ta' => $request->mo_ta,
            'trang_thai' => $request->trang_thai,
            'mau_sac' => $request->mau_sac,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm thể loại thành công!');
    }

    // Xem chi tiết thể loại
    public function show($id)
    {
        $category = Category::withCount('books')->findOrFail($id);
        
        // Lấy sách trong thể loại này
        $books = Book::where('category_id', $id)
            ->with(['borrows'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Thống kê
        $stats = [
            'total_books' => $books->total(),
            'total_borrows' => Book::where('category_id', $id)
                ->withCount('borrows')
                ->get()
                ->sum('borrows_count'),
            'popular_books' => Book::where('category_id', $id)
                ->withCount('borrows')
                ->orderBy('borrows_count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('admin.categories.show', compact('category', 'books', 'stats'));
    }

    // Form sửa
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Cập nhật thể loại
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_the_loai' => 'required|max:255|unique:categories,ten_the_loai,' . $id,
            'mo_ta' => 'nullable|max:500',
            'trang_thai' => 'required|in:active,inactive',
            'mau_sac' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'ten_the_loai' => $request->ten_the_loai,
            'mo_ta' => $request->mo_ta,
            'trang_thai' => $request->trang_thai,
            'mau_sac' => $request->mau_sac,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công!');
    }

    // Xóa thể loại
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Kiểm tra xem thể loại có sách không
        if ($category->books()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa thể loại có sách! Vui lòng chuyển sách sang thể loại khác trước.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Xóa thành công!');
    }

    // Xuất Excel
    public function export(Request $request)
    {
        return Excel::download(new CategoriesExport($request), 'danh_sach_the_loai_' . now()->format('Y-m-d') . '.xlsx');
    }

    // In danh sách
    public function print(Request $request)
    {
        $query = Category::withCount('books');

        // Áp dụng các bộ lọc giống như index
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('ten_the_loai', 'like', "%{$keyword}%");
        }

        $categories = $query->orderBy('ten_the_loai')->get();

        return view('admin.categories.print', compact('categories'));
    }

    // Thống kê thể loại
    public function statistics()
    {
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('trang_thai', 'active')->count(),
            'inactive_categories' => Category::where('trang_thai', 'inactive')->count(),
            'categories_with_books' => Category::has('books')->count(),
            'categories_without_books' => Category::doesntHave('books')->count(),
            'top_categories' => Category::withCount('books')
                ->orderBy('books_count', 'desc')
                ->limit(10)
                ->get(),
            'categories_by_status' => Category::selectRaw('trang_thai, COUNT(*) as count')
                ->groupBy('trang_thai')
                ->get(),
        ];

        return view('admin.categories.statistics', compact('stats'));
    }

    // Thao tác hàng loạt
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $categoryIds = $request->category_ids;

        if (empty($categoryIds)) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Vui lòng chọn ít nhất một thể loại!');
        }

        switch ($action) {
            case 'activate':
                Category::whereIn('id', $categoryIds)->update(['trang_thai' => 'active']);
                $message = 'Kích hoạt ' . count($categoryIds) . ' thể loại thành công!';
                break;
            case 'deactivate':
                Category::whereIn('id', $categoryIds)->update(['trang_thai' => 'inactive']);
                $message = 'Vô hiệu hóa ' . count($categoryIds) . ' thể loại thành công!';
                break;
            case 'delete':
                // Kiểm tra xem có thể loại nào có sách không
                $categoriesWithBooks = Category::whereIn('id', $categoryIds)
                    ->has('books')
                    ->count();
                
                if ($categoriesWithBooks > 0) {
                    return redirect()->route('admin.categories.index')
                        ->with('error', 'Không thể xóa thể loại có sách!');
                }

                Category::whereIn('id', $categoryIds)->delete();
                $message = 'Xóa ' . count($categoryIds) . ' thể loại thành công!';
                break;
            default:
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Hành động không hợp lệ!');
        }

        return redirect()->route('admin.categories.index')->with('success', $message);
    }

    // Chuyển sách sang thể loại khác
    public function moveBooks(Request $request, $id)
    {
        $request->validate([
            'target_category_id' => 'required|exists:categories,id',
        ]);

        $sourceCategory = Category::findOrFail($id);
        $targetCategory = Category::findOrFail($request->target_category_id);

        if ($sourceCategory->id === $targetCategory->id) {
            return redirect()->route('admin.categories.show', $id)
                ->with('error', 'Không thể chuyển sách sang cùng thể loại!');
        }

        $booksCount = $sourceCategory->books()->count();
        $sourceCategory->books()->update(['category_id' => $targetCategory->id]);

        return redirect()->route('admin.categories.show', $id)
            ->with('success', "Đã chuyển {$booksCount} sách từ '{$sourceCategory->ten_the_loai}' sang '{$targetCategory->ten_the_loai}'!");
    }
}
