<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_tac_gia' => 'required|max:255',
            'email' => 'required|email|unique:authors,email',
            'so_dien_thoai' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'ngay_sinh' => 'nullable|date|before:today',
            'gioi_thieu' => 'nullable|string',
            'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'trang_thai' => 'required|in:active,inactive',
        ]);

        $path = null;
        if ($request->hasFile('hinh_anh')) {
            try {
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/authors');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $filename);
                $path = 'authors/' . $filename;
            } catch (\Exception $e) {
                \Log::error('Author image upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Lỗi khi upload ảnh: ' . $e->getMessage());
            }
        }

        Author::create([
            'ten_tac_gia' => $request->ten_tac_gia,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_thieu' => $request->gioi_thieu,
            'hinh_anh' => $path,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('admin.authors.index')->with('success', 'Thêm tác giả thành công!');
    }

    public function show($id)
    {
        $author = Author::with(['books', 'purchasableBooks'])->findOrFail($id);
        return view('admin.authors.show', compact('author'));
    }

    public function edit($id)
    {
        $author = Author::findOrFail($id);
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $request->validate([
            'ten_tac_gia' => 'required|max:255',
            'email' => 'required|email|unique:authors,email,' . $id,
            'so_dien_thoai' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'ngay_sinh' => 'nullable|date|before:today',
            'gioi_thieu' => 'nullable|string',
            'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'trang_thai' => 'required|in:active,inactive',
        ]);

        $path = $author->hinh_anh;
        
        if ($request->hasFile('hinh_anh')) {
            try {
                // Xóa ảnh cũ nếu có
                if ($author->hinh_anh && Storage::disk('public')->exists($author->hinh_anh)) {
                    Storage::disk('public')->delete($author->hinh_anh);
                }
                
                // Lưu ảnh mới
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/authors');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $filename);
                $path = 'authors/' . $filename;
                
            } catch (\Exception $e) {
                \Log::error('Author image upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Lỗi khi upload ảnh: ' . $e->getMessage());
            }
        }

        $author->update([
            'ten_tac_gia' => $request->ten_tac_gia,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_thieu' => $request->gioi_thieu,
            'hinh_anh' => $path,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('admin.authors.index')->with('success', 'Cập nhật tác giả thành công!');
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        
        // Xóa ảnh nếu có
        if ($author->hinh_anh && Storage::disk('public')->exists($author->hinh_anh)) {
            Storage::disk('public')->delete($author->hinh_anh);
        }
        
        $author->delete();
        return redirect()->route('admin.authors.index')->with('success', 'Xóa tác giả thành công!');
    }
}