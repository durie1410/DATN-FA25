<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FacultyController extends Controller
{
    /**
     * Display a listing of faculties
     */
    public function index(Request $request)
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

        $faculties = $query->paginate(15);

        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new faculty
     */
    public function create()
    {
        return view('admin.faculties.create');
    }

    /**
     * Store a newly created faculty
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_khoa' => 'required|string|max:255',
            'ma_khoa' => 'required|string|max:50|unique:faculties',
            'mo_ta' => 'nullable|string|max:1000',
            'truong_khoa' => 'nullable|string|max:255',
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
            $logo->storeAs('public/faculties', $logoName);
            $data['logo'] = 'faculties/' . $logoName;
        }

        Faculty::create($data);

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Thêm khoa thành công!');
    }

    /**
     * Display the specified faculty
     */
    public function show($id)
    {
        $faculty = Faculty::with(['departments' => function($query) {
            $query->where('trang_thai', 'active');
        }, 'readers' => function($query) {
            $query->where('trang_thai', 'active');
        }])->findOrFail($id);

        return view('admin.faculties.show', compact('faculty'));
    }

    /**
     * Show the form for editing the specified faculty
     */
    public function edit($id)
    {
        $faculty = Faculty::findOrFail($id);
        return view('admin.faculties.edit', compact('faculty'));
    }

    /**
     * Update the specified faculty
     */
    public function update(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'ten_khoa' => 'required|string|max:255',
            'ma_khoa' => 'required|string|max:50|unique:faculties,ma_khoa,' . $id,
            'mo_ta' => 'nullable|string|max:1000',
            'truong_khoa' => 'nullable|string|max:255',
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
            if ($faculty->logo) {
                Storage::delete('public/' . $faculty->logo);
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('public/faculties', $logoName);
            $data['logo'] = 'faculties/' . $logoName;
        }

        $faculty->update($data);

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Cập nhật khoa thành công!');
    }

    /**
     * Remove the specified faculty
     */
    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);

        // Check if faculty can be deleted
        if (!$faculty->canDelete()) {
            return redirect()->back()
                ->with('error', 'Không thể xóa khoa này vì đang có ngành học hoặc sinh viên thuộc khoa này!');
        }

        // Delete logo if exists
        if ($faculty->logo) {
            Storage::delete('public/' . $faculty->logo);
        }

        $faculty->delete();

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Xóa khoa thành công!');
    }

    /**
     * Toggle faculty status
     */
    public function toggleStatus($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->trang_thai = $faculty->trang_thai === 'active' ? 'inactive' : 'active';
        $faculty->save();

        $status = $faculty->trang_thai === 'active' ? 'kích hoạt' : 'tạm dừng';
        return redirect()->back()
            ->with('success', "Đã {$status} khoa thành công!");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một khoa!');
        }

        switch ($action) {
            case 'activate':
                Faculty::whereIn('id', $ids)->update(['trang_thai' => 'active']);
                return redirect()->back()->with('success', 'Đã kích hoạt các khoa được chọn!');
                
            case 'deactivate':
                Faculty::whereIn('id', $ids)->update(['trang_thai' => 'inactive']);
                return redirect()->back()->with('success', 'Đã tạm dừng các khoa được chọn!');
                
            case 'delete':
                $faculties = Faculty::whereIn('id', $ids)->get();
                $canDelete = $faculties->every(function($faculty) {
                    return $faculty->canDelete();
                });

                if (!$canDelete) {
                    return redirect()->back()->with('error', 'Một số khoa không thể xóa vì đang có ngành học hoặc sinh viên!');
                }

                // Delete logos
                foreach ($faculties as $faculty) {
                    if ($faculty->logo) {
                        Storage::delete('public/' . $faculty->logo);
                    }
                }

                Faculty::whereIn('id', $ids)->delete();
                return redirect()->back()->with('success', 'Đã xóa các khoa được chọn!');
                
            default:
                return redirect()->back()->with('error', 'Hành động không hợp lệ!');
        }
    }
}
