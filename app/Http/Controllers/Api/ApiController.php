<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Reader;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Thư Viện Online API',
            'version' => '1.0.0',
            'status' => 'success',
            'endpoints' => [
                'books' => '/api/books',
                'categories' => '/api/categories',
                'departments' => '/api/departments',
                'faculties' => '/api/faculties',
                'publishers' => '/api/publishers',
                'readers' => '/api/readers',
                'borrows' => '/api/borrows',
                'auth' => '/api/auth',
            ]
        ]);
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'total_readers' => Reader::count(),
            'active_borrows' => Borrow::where('trang_thai', 'Dang muon')->count(),
            'overdue_borrows' => Borrow::where('trang_thai', 'Dang muon')
                ->where('ngay_hen_tra', '<', now()->toDateString())
                ->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}