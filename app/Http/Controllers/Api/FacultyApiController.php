<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FacultyApiController extends Controller
{
    /**
     * Display a listing of faculties
     */
    public function index(Request $request): JsonResponse
    {
        $query = Faculty::withCount(['departments', 'readers']);

        // Search by name or code
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_khoa', 'like', "%{$keyword}%")
                  ->orWhere('ma_khoa', 'like', "%{$keyword}%");
            });
        }

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'ten_khoa':
                $query->orderBy('ten_khoa', $sortOrder);
                break;
            case 'ma_khoa':
                $query->orderBy('ma_khoa', $sortOrder);
                break;
            case 'departments_count':
                $query->orderBy('departments_count', $sortOrder);
                break;
            case 'readers_count':
                $query->orderBy('readers_count', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $faculties = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $faculties
        ]);
    }

    /**
     * Display the specified faculty
     */
    public function show($id): JsonResponse
    {
        $faculty = Faculty::with(['departments', 'readers'])->find($id);

        if (!$faculty) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy khoa'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $faculty
        ]);
    }

    /**
     * Get active faculties
     */
    public function getActive(): JsonResponse
    {
        $faculties = Faculty::where('trang_thai', 'active')
                           ->orderBy('ten_khoa')
                           ->get();

        return response()->json([
            'status' => 'success',
            'data' => $faculties
        ]);
    }

    /**
     * Get faculties with departments
     */
    public function getWithDepartments(): JsonResponse
    {
        $faculties = Faculty::with(['departments' => function($query) {
                                $query->where('trang_thai', 'active');
                            }])
                           ->where('trang_thai', 'active')
                           ->orderBy('ten_khoa')
                           ->get();

        return response()->json([
            'status' => 'success',
            'data' => $faculties
        ]);
    }
}
