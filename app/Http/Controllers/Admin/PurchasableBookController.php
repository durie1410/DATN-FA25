<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchasableBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchasableBookController extends Controller
{
    public function index()
    {
        $books = PurchasableBook::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.purchasable-books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.purchasable-books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_sach' => 'required|max:255',
            'tac_gia' => 'required',
            'mo_ta' => 'nullable',
            'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'gia' => 'required|numeric|min:0',
            'nha_xuat_ban' => 'required',
            'nam_xuat_ban' => 'required|digits:4',
            'isbn' => 'nullable',
            'so_trang' => 'nullable|integer',
            'ngon_ngu' => 'nullable',
            'dinh_dang' => 'required',
            'kich_thuoc_file' => 'nullable|integer',
            'trang_thai' => 'required|in:active,inactive',
        ]);

        $path = null;
        if ($request->hasFile('hinh_anh')) {
            try {
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/books');
                
                // Đảm bảo thư mục tồn tại
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $filename);
                $path = 'books/' . $filename;
            } catch (\Exception $e) {
                \Log::error('Upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Lỗi khi upload ảnh: ' . $e->getMessage());
            }
        }

        try {
            PurchasableBook::create([
                'ten_sach' => $request->ten_sach,
                'tac_gia' => $request->tac_gia,
                'mo_ta' => $request->mo_ta,
                'hinh_anh' => $path,
                'gia' => $request->gia,
                'nha_xuat_ban' => $request->nha_xuat_ban,
                'nam_xuat_ban' => $request->nam_xuat_ban,
                'isbn' => $request->isbn,
                'so_trang' => $request->so_trang,
                'ngon_ngu' => $request->ngon_ngu,
                'dinh_dang' => $request->dinh_dang,
                'kich_thuoc_file' => $request->kich_thuoc_file,
                'trang_thai' => $request->trang_thai,
                'so_luong_ban' => 0,
                'danh_gia_trung_binh' => 0,
                'so_luot_xem' => 0,
            ]);

            return redirect()->route('admin.purchasable-books.index')->with('success', 'Thêm sách thành công!');
        } catch (\Exception $e) {
            \Log::error('Create PurchasableBook error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Lỗi khi tạo sách: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $book = PurchasableBook::findOrFail($id);
        return view('admin.purchasable-books.show', compact('book'));
    }

    public function edit($id)
    {
        $book = PurchasableBook::findOrFail($id);
        return view('admin.purchasable-books.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $book = PurchasableBook::findOrFail($id);

        $request->validate([
            'ten_sach' => 'required|max:255',
            'tac_gia' => 'required',
            'mo_ta' => 'nullable',
            'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'gia' => 'required|numeric|min:0',
            'nha_xuat_ban' => 'required',
            'nam_xuat_ban' => 'required|digits:4',
            'isbn' => 'nullable',
            'so_trang' => 'nullable|integer',
            'ngon_ngu' => 'nullable',
            'dinh_dang' => 'required',
            'kich_thuoc_file' => 'nullable|integer',
            'trang_thai' => 'required|in:active,inactive',
        ]);

        $path = $book->hinh_anh;
        
        if ($request->hasFile('hinh_anh')) {
            try {
                // Xóa ảnh cũ nếu có
                if ($book->hinh_anh && Storage::disk('public')->exists($book->hinh_anh)) {
                    Storage::disk('public')->delete($book->hinh_anh);
                }
                
                // Lưu ảnh mới
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/books');
                
                // Đảm bảo thư mục tồn tại
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $filename);
                $path = 'books/' . $filename;
                
            } catch (\Exception $e) {
                \Log::error('Upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Lỗi khi upload ảnh: ' . $e->getMessage());
            }
        }

        $book->update([
            'ten_sach' => $request->ten_sach,
            'tac_gia' => $request->tac_gia,
            'mo_ta' => $request->mo_ta,
            'hinh_anh' => $path,
            'gia' => $request->gia,
            'nha_xuat_ban' => $request->nha_xuat_ban,
            'nam_xuat_ban' => $request->nam_xuat_ban,
            'isbn' => $request->isbn,
            'so_trang' => $request->so_trang,
            'ngon_ngu' => $request->ngon_ngu,
            'dinh_dang' => $request->dinh_dang,
            'kich_thuoc_file' => $request->kich_thuoc_file,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('admin.purchasable-books.index')->with('success', 'Cập nhật sách thành công!');
    }

    public function destroy($id)
    {
        $book = PurchasableBook::findOrFail($id);
        
        // Xóa ảnh nếu có
        if ($book->hinh_anh && Storage::disk('public')->exists($book->hinh_anh)) {
            Storage::disk('public')->delete($book->hinh_anh);
        }
        
        $book->delete();
        return redirect()->route('admin.purchasable-books.index')->with('success', 'Xóa sách thành công!');
    }
}

