<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Reader;
use App\Models\Librarian;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create 
                            {name : Tên người dùng}
                            {email : Email người dùng}
                            {role : Vai trò (admin/staff/user)}
                            {--password=123456 : Mật khẩu}
                            {--faculty= : Mã khoa (cho user)}
                            {--department= : Mã ngành (cho user)}
                            {--position= : Chức vụ (cho staff)}
                            {--phone= : Số điện thoại}
                            {--address= : Địa chỉ}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo người dùng mới trong hệ thống';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $role = $this->argument('role');
        $password = $this->option('password');
        $faculty = $this->option('faculty');
        $department = $this->option('department');
        $position = $this->option('position');
        $phone = $this->option('phone');
        $address = $this->option('address');

        // Validate role
        if (!in_array($role, ['admin', 'staff', 'user'])) {
            $this->error('Vai trò không hợp lệ. Chỉ chấp nhận: admin, staff, user');
            return 1;
        }

        // Check if email already exists
        if (User::where('email', $email)->exists()) {
            $this->error('Email đã tồn tại trong hệ thống!');
            return 1;
        }

        try {
            // Create User
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role
            ]);

            $this->info("✅ Đã tạo User: {$name} ({$email})");

            // Create specific profile based on role
            if ($role === 'staff') {
                $this->createLibrarian($user, $position, $phone, $address);
            } elseif ($role === 'user') {
                $this->createReader($user, $faculty, $department, $phone, $address);
            }

            $this->info("🎉 Hoàn thành tạo người dùng!");
            $this->table(
                ['Thông tin', 'Giá trị'],
                [
                    ['Tên', $name],
                    ['Email', $email],
                    ['Vai trò', $role],
                    ['Mật khẩu', $password],
                ]
            );

        } catch (\Exception $e) {
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Create Librarian profile
     */
    protected function createLibrarian($user, $position, $phone, $address)
    {
        $maThuThu = 'TT' . str_pad(Librarian::count() + 1, 3, '0', STR_PAD_LEFT);
        
        $librarian = Librarian::create([
            'user_id' => $user->id,
            'ho_ten' => $user->name,
            'ma_thu_thu' => $maThuThu,
            'email' => $user->email,
            'so_dien_thoai' => $phone ?: 'Chưa cập nhật',
            'ngay_sinh' => now()->subYears(25),
            'gioi_tinh' => 'male',
            'dia_chi' => $address ?: 'Chưa cập nhật',
            'chuc_vu' => $position ?: 'Nhân viên thư viện',
            'phong_ban' => 'Phòng Phục vụ Độc giả',
            'ngay_vao_lam' => now(),
            'ngay_het_han_hop_dong' => now()->addYear(),
            'luong_co_ban' => 10000000,
            'trang_thai' => 'active',
            'bang_cap' => 'Đại học',
            'kinh_nghiem' => 'Mới vào làm',
            'ghi_chu' => 'Tạo bằng command line'
        ]);

        $this->info("📚 Đã tạo Librarian: {$maThuThu}");
    }

    /**
     * Create Reader profile
     */
    protected function createReader($user, $faculty, $department, $phone, $address)
    {
        $soTheDocGia = 'RD' . str_pad(Reader::count() + 1, 3, '0', STR_PAD_LEFT);
        
        // Get faculty and department
        $facultyModel = null;
        $departmentModel = null;
        
        if ($faculty) {
            $facultyModel = Faculty::where('ma_khoa', $faculty)->first();
            if (!$facultyModel) {
                $this->warn("Không tìm thấy khoa với mã: {$faculty}");
            }
        }
        
        if ($department) {
            $departmentModel = Department::where('ma_nganh', $department)->first();
            if (!$departmentModel) {
                $this->warn("Không tìm thấy ngành với mã: {$department}");
            }
        }

        $reader = Reader::create([
            'user_id' => $user->id,
            'ho_ten' => $user->name,
            'email' => $user->email,
            'so_dien_thoai' => $phone ?: 'Chưa cập nhật',
            'ngay_sinh' => now()->subYears(20),
            'gioi_tinh' => 'Nam',
            'dia_chi' => $address ?: 'Chưa cập nhật',
            'so_the_doc_gia' => $soTheDocGia,
            'ngay_cap_the' => now(),
            'ngay_het_han' => now()->addYear(),
            'trang_thai' => 'Hoat dong',
            'faculty_id' => $facultyModel ? $facultyModel->id : null,
            'department_id' => $departmentModel ? $departmentModel->id : null
        ]);

        $this->info("👥 Đã tạo Reader: {$soTheDocGia}");
    }
}
