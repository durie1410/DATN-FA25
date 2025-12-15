<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublisherApiController extends Controller
{
    /**
     * Display a listing of publishers
     */
    public function index(Request $request): JsonResponse
    {
        $query = Publisher::withCount('books');

        // Search by name
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('ten_nha_xuat_ban', 'like', "%{$keyword}%");
        }

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'ten_nha_xuat_ban':
                $query->orderBy('ten_nha_xuat_ban', $sortOrder);
                break;
            case 'books_count':
                $query->orderBy('books_count', $sortOrder);
                break;
            case 'ngay_thanh_lap':
                $query->orderBy('ngay_thanh_lap', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $publishers = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $publishers
        ]);
    }

    /**
     * Display the specified publisher
     */
    public function show($id): JsonResponse
    {
        $publisher = Publisher::with('books')->find($id);

        if (!$publisher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy nhà xuất bản'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $publisher
        ]);
    }

    /**
     * Get active publishers
     */
    public function getActive(): JsonResponse
    {
        $publishers = Publisher::where('trang_thai', 'active')
                              ->orderBy('ten_nha_xuat_ban')
                              ->get();

        return response()->json([
            'status' => 'success',
            'data' => $publishers
        ]);
    }

    /**
     * Get publishers with books
     */
    public function getWithBooks(): JsonResponse
    {
        $publishers = Publisher::with(['books' => function($query) {
                                   $query->where('trang_thai', 'active');
                               }])
                              ->where('trang_thai', 'active')
                              ->orderBy('ten_nha_xuat_ban')
                              ->get();

        return response()->json([
            'status' => 'success',
            'data' => $publishers
        ]);
    }

    /**
     * Get popular publishers (sorted by book count)
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        
        $publishers = Publisher::withCount('books')
                              ->where('trang_thai', 'active')
                              ->orderBy('books_count', 'desc')
                              ->limit($limit)
                              ->get();

        return response()->json([
            'status' => 'success',
            'data' => $publishers
        ]);
    }

    /**
     * Get recent publishers
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        
        $publishers = Publisher::withCount('books')
                              ->where('trang_thai', 'active')
                              ->orderBy('created_at', 'desc')
                              ->limit($limit)
                              ->get();

        return response()->json([
            'status' => 'success',
            'data' => $publishers
        ]);
    }

    /**
     * Get publishers with top books
     */
    public function topBooks(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        
        $publishers = Publisher::withCount(['books' => function($query) {
                                    $query->where('trang_thai', 'active');
                                }])
                              ->where('trang_thai', 'active')
                              ->having('books_count', '>', 0)
                              ->orderBy('books_count', 'desc')
                              ->limit($limit)
                              ->get();

        return response()->json([
            'status' => 'success',
            'data' => $publishers
        ]);
    }

    /**
     * Search publishers
     */
    public function search(Request $request): JsonResponse
    {
        $query = Publisher::withCount('books');

        // Search by name
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('ten_nha_xuat_ban', 'like', "%{$keyword}%");
        }

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        } else {
            $query->where('trang_thai', 'active');
        }

        // Limit results
        $limit = $request->get('limit', 20);
        $publishers = $query->orderBy('ten_nha_xuat_ban')
                            ->limit($limit)
                            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $publishers,
            'total' => $publishers->count()
        ]);
    }
}
