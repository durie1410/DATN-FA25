<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DepartmentApiController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index(Request $request): JsonResponse
    {
        $query = Department::with('faculty');

        // Search by name or code
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_nganh', 'like', "%{$keyword}%")
                  ->orWhere('ma_nganh', 'like', "%{$keyword}%");
            });
        }

        // Filter by faculty
        if ($request->filled('faculty_id')) {
            $query->where('faculty_id', $request->faculty_id);
        }

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'ten_nganh':
                $query->orderBy('ten_nganh', $sortOrder);
                break;
            case 'ma_nganh':
                $query->orderBy('ma_nganh', $sortOrder);
                break;
            case 'faculty':
                $query->join('faculties', 'departments.faculty_id', '=', 'faculties.id')
                      ->orderBy('faculties.ten_khoa', $sortOrder)
                      ->select('departments.*');
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $departments = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ]);
    }

    /**
     * Display the specified department
     */
    public function show($id): JsonResponse
    {
        $department = Department::with(['faculty', 'readers'])->find($id);

        if (!$department) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy ngành học'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $department
        ]);
    }

    /**
     * Get departments by faculty
     */
    public function getByFaculty($facultyId): JsonResponse
    {
        $departments = Department::where('faculty_id', $facultyId)
                                ->where('trang_thai', 'active')
                                ->orderBy('ten_nganh')
                                ->get();

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ]);
    }

    /**
     * Get active departments
     */
    public function getActive(): JsonResponse
    {
        $departments = Department::with('faculty')
                                ->where('trang_thai', 'active')
                                ->orderBy('ten_nganh')
                                ->get();

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ]);
    }
}
