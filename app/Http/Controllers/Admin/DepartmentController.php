<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index(Request $request)
    {
        $query = Department::with(['faculty'])->withCount('readers');

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
            case 'readers_count':
                $query->orderBy('readers_count', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $departments = $query->paginate(15);
        $faculties = Faculty::where('trang_thai', 'active')->orderBy('ten_khoa')->get();

        return view('admin.departments.index', compact('departments', 'faculties'));
    }

    /**
     * Show the form for creating a new department
     */
    public function create()
    {
        $faculties = Faculty::where('trang_thai', 'active')->orderBy('ten_khoa')->get();
        return view('admin.departments.create', compact('faculties'));
    }

    /**
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_nganh' => 'required|string|max:255',
            'ma_nganh' => 'required|string|max:50|unique:departments',
            'faculty_id' => 'required|exists:faculties,id',
            'mo_ta' => 'nullable|string|max:1000',
            'truong_nganh' => 'nullable|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'dia_chi' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'ngay_thanh_lap' => 'nullable|date',
            'trang_thai' => 'required|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('public/departments', $logoName);
            $data['logo'] = 'departments/' . $logoName;
        }

        Department::create($data);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Thêm ngành học thành công!');
    }

    /**
     * Display the specified department
     */
    public function show($id)
    {
        $department = Department::with(['faculty', 'readers' => function($query) {
            $query->where('trang_thai', 'active');
        }])->findOrFail($id);

        return view('admin.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $faculties = Faculty::where('trang_thai', 'active')->orderBy('ten_khoa')->get();
        
        return view('admin.departments.edit', compact('department', 'faculties'));
    }

    /**
     * Update the specified department
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'ten_nganh' => 'required|string|max:255',
            'ma_nganh' => 'required|string|max:50|unique:departments,ma_nganh,' . $id,
            'faculty_id' => 'required|exists:faculties,id',
            'mo_ta' => 'nullable|string|max:1000',
            'truong_nganh' => 'nullable|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'dia_chi' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'ngay_thanh_lap' => 'nullable|date',
            'trang_thai' => 'required|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($department->logo) {
                Storage::delete('public/' . $department->logo);
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('public/departments', $logoName);
            $data['logo'] = 'departments/' . $logoName;
        }

        $department->update($data);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Cập nhật ngành học thành công!');
    }

    /**
     * Remove the specified department
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        // Check if department can be deleted
        if (!$department->canDelete()) {
            return redirect()->back()
                ->with('error', 'Không thể xóa ngành học này vì đang có sinh viên thuộc ngành này!');
        }

        // Delete logo if exists
        if ($department->logo) {
            Storage::delete('public/' . $department->logo);
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Xóa ngành học thành công!');
    }

    /**
     * Toggle department status
     */
    public function toggleStatus($id)
    {
        $department = Department::findOrFail($id);
        $department->trang_thai = $department->trang_thai === 'active' ? 'inactive' : 'active';
        $department->save();

        $status = $department->trang_thai === 'active' ? 'kích hoạt' : 'tạm dừng';
        return redirect()->back()
            ->with('success', "Đã {$status} ngành học thành công!");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một ngành học!');
        }

        switch ($action) {
            case 'activate':
                Department::whereIn('id', $ids)->update(['trang_thai' => 'active']);
                return redirect()->back()->with('success', 'Đã kích hoạt các ngành học được chọn!');
                
            case 'deactivate':
                Department::whereIn('id', $ids)->update(['trang_thai' => 'inactive']);
                return redirect()->back()->with('success', 'Đã tạm dừng các ngành học được chọn!');
                
            case 'delete':
                $departments = Department::whereIn('id', $ids)->get();
                $canDelete = $departments->every(function($department) {
                    return $department->canDelete();
                });

                if (!$canDelete) {
                    return redirect()->back()->with('error', 'Một số ngành học không thể xóa vì đang có sinh viên!');
                }

                // Delete logos
                foreach ($departments as $department) {
                    if ($department->logo) {
                        Storage::delete('public/' . $department->logo);
                    }
                }

                Department::whereIn('id', $ids)->delete();
                return redirect()->back()->with('success', 'Đã xóa các ngành học được chọn!');
                
            default:
                return redirect()->back()->with('error', 'Hành động không hợp lệ!');
        }
    }
}
