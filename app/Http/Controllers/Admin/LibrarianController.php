<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Librarian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LibrarianController extends Controller
{
    public function index(Request $request)
    {
        $query = Librarian::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ma_thu_thu', 'like', "%{$search}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$search}%");
            });
        }

        // Filter by position
        if ($request->filled('chuc_vu')) {
            $query->where('chuc_vu', $request->get('chuc_vu'));
        }

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->get('trang_thai'));
        }

        // Filter by department
        if ($request->filled('phong_ban')) {
            $query->where('phong_ban', $request->get('phong_ban'));
        }

        $librarians = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $totalLibrarians = Librarian::count();
        $activeLibrarians = Librarian::where('trang_thai', 'active')->count();
        $inactiveLibrarians = Librarian::where('trang_thai', 'inactive')->count();
        $expiringContracts = Librarian::where('ngay_het_han_hop_dong', '<=', now()->addDays(30))
            ->where('ngay_het_han_hop_dong', '>', now())
            ->count();

        return view('admin.librarians.index', compact(
            'librarians',
            'totalLibrarians',
            'activeLibrarians',
            'inactiveLibrarians',
            'expiringContracts'
        ));
    }

    public function create()
    {
        return view('admin.librarians.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'ho_ten' => 'required|string|max:255',
            'ma_thu_thu' => 'required|string|max:20|unique:librarians',
            'so_dien_thoai' => 'required|string|max:20',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:male,female,other',
            'dia_chi' => 'required|string',
            'chuc_vu' => 'required|string|max:255',
            'phong_ban' => 'required|string|max:255',
            'ngay_vao_lam' => 'required|date',
            'ngay_het_han_hop_dong' => 'required|date|after:ngay_vao_lam',
            'luong_co_ban' => 'required|numeric|min:0',
            'bang_cap' => 'nullable|string',
            'kinh_nghiem' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
        ]);

        // Create Librarian
        $librarian = Librarian::create([
            'user_id' => $user->id,
            'ho_ten' => $request->ho_ten,
            'ma_thu_thu' => $request->ma_thu_thu,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'dia_chi' => $request->dia_chi,
            'chuc_vu' => $request->chuc_vu,
            'phong_ban' => $request->phong_ban,
            'ngay_vao_lam' => $request->ngay_vao_lam,
            'ngay_het_han_hop_dong' => $request->ngay_het_han_hop_dong,
            'luong_co_ban' => $request->luong_co_ban,
            'trang_thai' => 'active',
            'bang_cap' => $request->bang_cap,
            'kinh_nghiem' => $request->kinh_nghiem,
            'ghi_chu' => $request->ghi_chu,
        ]);

        return redirect()->route('admin.librarians.index')
            ->with('success', 'Thủ thư đã được tạo thành công!');
    }

    public function show(Librarian $librarian)
    {
        $librarian->load('user');
        return view('admin.librarians.show', compact('librarian'));
    }

    public function edit(Librarian $librarian)
    {
        $librarian->load('user');
        return view('admin.librarians.edit', compact('librarian'));
    }

    public function update(Request $request, Librarian $librarian)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $librarian->user_id,
            'ho_ten' => 'required|string|max:255',
            'ma_thu_thu' => 'required|string|max:20|unique:librarians,ma_thu_thu,' . $librarian->id,
            'so_dien_thoai' => 'required|string|max:20',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:male,female,other',
            'dia_chi' => 'required|string',
            'chuc_vu' => 'required|string|max:255',
            'phong_ban' => 'required|string|max:255',
            'ngay_vao_lam' => 'required|date',
            'ngay_het_han_hop_dong' => 'required|date|after:ngay_vao_lam',
            'luong_co_ban' => 'required|numeric|min:0',
            'trang_thai' => 'required|in:active,inactive',
            'bang_cap' => 'nullable|string',
            'kinh_nghiem' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update User
        $librarian->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update Librarian
        $librarian->update([
            'ho_ten' => $request->ho_ten,
            'ma_thu_thu' => $request->ma_thu_thu,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'dia_chi' => $request->dia_chi,
            'chuc_vu' => $request->chuc_vu,
            'phong_ban' => $request->phong_ban,
            'ngay_vao_lam' => $request->ngay_vao_lam,
            'ngay_het_han_hop_dong' => $request->ngay_het_han_hop_dong,
            'luong_co_ban' => $request->luong_co_ban,
            'trang_thai' => $request->trang_thai,
            'bang_cap' => $request->bang_cap,
            'kinh_nghiem' => $request->kinh_nghiem,
            'ghi_chu' => $request->ghi_chu,
        ]);

        return redirect()->route('admin.librarians.index')
            ->with('success', 'Thông tin thủ thư đã được cập nhật!');
    }

    public function destroy(Librarian $librarian)
    {
        // Delete user and librarian
        $librarian->user->delete();
        $librarian->delete();

        return redirect()->route('admin.librarians.index')
            ->with('success', 'Thủ thư đã được xóa!');
    }

    public function toggleStatus(Librarian $librarian)
    {
        $librarian->update([
            'trang_thai' => $librarian->trang_thai === 'active' ? 'inactive' : 'active'
        ]);

        $status = $librarian->trang_thai === 'active' ? 'kích hoạt' : 'vô hiệu hóa';
        return response()->json([
            'success' => true,
            'message' => "Đã {$status} thủ thư {$librarian->ho_ten}",
            'status' => $librarian->trang_thai
        ]);
    }

    public function renewContract(Request $request, Librarian $librarian)
    {
        $request->validate([
            'ngay_het_han_moi' => 'required|date|after:today',
            'luong_moi' => 'nullable|numeric|min:0',
        ]);

        $librarian->update([
            'ngay_het_han_hop_dong' => $request->ngay_het_han_moi,
            'luong_co_ban' => $request->luong_moi ?? $librarian->luong_co_ban,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hợp đồng đã được gia hạn thành công!'
        ]);
    }

    public function export()
    {
        $librarians = Librarian::with('user')->get();
        
        $filename = 'librarians_' . date('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($librarians) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Mã thủ thư',
                'Họ tên',
                'Email',
                'Số điện thoại',
                'Ngày sinh',
                'Giới tính',
                'Địa chỉ',
                'Chức vụ',
                'Phòng ban',
                'Ngày vào làm',
                'Ngày hết hạn hợp đồng',
                'Lương cơ bản',
                'Trạng thái',
                'Bằng cấp',
                'Kinh nghiệm',
                'Ghi chú'
            ]);

            // Data rows
            foreach ($librarians as $librarian) {
                fputcsv($file, [
                    $librarian->ma_thu_thu,
                    $librarian->ho_ten,
                    $librarian->email,
                    $librarian->so_dien_thoai,
                    $librarian->ngay_sinh ? $librarian->ngay_sinh->format('d/m/Y') : '',
                    $librarian->gioi_tinh === 'male' ? 'Nam' : ($librarian->gioi_tinh === 'female' ? 'Nữ' : 'Khác'),
                    $librarian->dia_chi,
                    $librarian->chuc_vu,
                    $librarian->phong_ban,
                    $librarian->ngay_vao_lam ? $librarian->ngay_vao_lam->format('d/m/Y') : '',
                    $librarian->ngay_het_han_hop_dong ? $librarian->ngay_het_han_hop_dong->format('d/m/Y') : '',
                    number_format($librarian->luong_co_ban, 0, ',', '.'),
                    $librarian->trang_thai === 'active' ? 'Hoạt động' : 'Tạm dừng',
                    $librarian->bang_cap,
                    $librarian->kinh_nghiem,
                    $librarian->ghi_chu
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn ít nhất một thủ thư!'
            ]);
        }

        switch ($action) {
            case 'activate':
                Librarian::whereIn('id', $ids)->update(['trang_thai' => 'active']);
                $message = 'Đã kích hoạt ' . count($ids) . ' thủ thư!';
                break;
            case 'deactivate':
                Librarian::whereIn('id', $ids)->update(['trang_thai' => 'inactive']);
                $message = 'Đã vô hiệu hóa ' . count($ids) . ' thủ thư!';
                break;
            case 'delete':
                $librarians = Librarian::whereIn('id', $ids)->with('user')->get();
                foreach ($librarians as $librarian) {
                    $librarian->user->delete();
                    $librarian->delete();
                }
                $message = 'Đã xóa ' . count($ids) . ' thủ thư!';
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Hành động không hợp lệ!'
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
