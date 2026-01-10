<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use App\Services\CacheService;

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
        
        // Clear cache khi xóa category
        CacheService::clearCategories();
        CacheService::clearDashboard();
        
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
