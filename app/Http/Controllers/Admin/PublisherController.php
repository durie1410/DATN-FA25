<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    /**
     * Display a listing of publishers
     */
    public function index(Request $request)
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
        $sortOrder = $request->get('sort_order', 'desc');
        
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

        $publishers = $query->paginate(15);

        return view('admin.publishers.index', compact('publishers'));
    }

    /**
     * Show the form for creating a new publisher
     */
    public function create()
    {
        return view('admin.publishers.create');
    }

    /**
     * Store a newly created publisher
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_nha_xuat_ban' => 'required|string|max:255|unique:publishers',
            'dia_chi' => 'nullable|string|max:500',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'mo_ta' => 'nullable|string|max:1000',
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
            $logo->storeAs('public/publishers', $logoName);
            $data['logo'] = 'publishers/' . $logoName;
        }

        Publisher::create($data);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Thêm nhà xuất bản thành công!');
    }

    /**
     * Display the specified publisher
     */
    public function show($id)
    {
        $publisher = Publisher::with(['books' => function($query) {
            $query->where('trang_thai', 'active');
        }])->findOrFail($id);

        return view('admin.publishers.show', compact('publisher'));
    }

    /**
     * Show the form for editing the specified publisher
     */
    public function edit($id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('admin.publishers.edit', compact('publisher'));
    }

    /**
     * Update the specified publisher
     */
    public function update(Request $request, $id)
    {
        $publisher = Publisher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'ten_nha_xuat_ban' => 'required|string|max:255|unique:publishers,ten_nha_xuat_ban,' . $id,
            'dia_chi' => 'nullable|string|max:500',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'mo_ta' => 'nullable|string|max:1000',
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
            if ($publisher->logo) {
                Storage::delete('public/' . $publisher->logo);
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('public/publishers', $logoName);
            $data['logo'] = 'publishers/' . $logoName;
        }

        $publisher->update($data);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Cập nhật nhà xuất bản thành công!');
    }

    /**
     * Remove the specified publisher
     */
    public function destroy($id)
    {
        $publisher = Publisher::findOrFail($id);

        // Check if publisher can be deleted
        if (!$publisher->canDelete()) {
            return redirect()->back()
                ->with('error', 'Không thể xóa nhà xuất bản này vì đang có sách thuộc về nhà xuất bản này!');
        }

        // Delete logo if exists
        if ($publisher->logo) {
            Storage::delete('public/' . $publisher->logo);
        }

        $publisher->delete();

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Xóa nhà xuất bản thành công!');
    }

    /**
     * Toggle publisher status
     */
    public function toggleStatus($id)
    {
        $publisher = Publisher::findOrFail($id);
        $publisher->trang_thai = $publisher->trang_thai === 'active' ? 'inactive' : 'active';
        $publisher->save();

        $status = $publisher->trang_thai === 'active' ? 'kích hoạt' : 'tạm dừng';
        return redirect()->back()
            ->with('success', "Đã {$status} nhà xuất bản thành công!");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một nhà xuất bản!');
        }

        switch ($action) {
            case 'activate':
                Publisher::whereIn('id', $ids)->update(['trang_thai' => 'active']);
                return redirect()->back()->with('success', 'Đã kích hoạt các nhà xuất bản được chọn!');
                
            case 'deactivate':
                Publisher::whereIn('id', $ids)->update(['trang_thai' => 'inactive']);
                return redirect()->back()->with('success', 'Đã tạm dừng các nhà xuất bản được chọn!');
                
            case 'delete':
                $publishers = Publisher::whereIn('id', $ids)->get();
                $canDelete = $publishers->every(function($publisher) {
                    return $publisher->canDelete();
                });

                if (!$canDelete) {
                    return redirect()->back()->with('error', 'Một số nhà xuất bản không thể xóa vì đang có sách!');
                }

                // Delete logos
                foreach ($publishers as $publisher) {
                    if ($publisher->logo) {
                        Storage::delete('public/' . $publisher->logo);
                    }
                }

                Publisher::whereIn('id', $ids)->delete();
                return redirect()->back()->with('success', 'Đã xóa các nhà xuất bản được chọn!');
                
            default:
                return redirect()->back()->with('error', 'Hành động không hợp lệ!');
        }
    }
}
