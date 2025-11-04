<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reader;
use App\Models\Librarian;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('🚀 Bắt đầu tạo dữ liệu người dùng...');

        // Tạo Faculty và Department trước
        $this->createFacultiesAndDepartments();

        // Tạo Admin Users
        $this->createAdminUsers();

        // Tạo Librarian Users
        $this->createLibrarianUsers();

        // Tạo Reader Users
        $this->createReaderUsers();

        $this->command->info('✅ Hoàn thành tạo dữ liệu người dùng!');
    }

    /**
     * Tạo Faculty và Department
     */
    protected function createFacultiesAndDepartments()
    {
        $this->command->info('📚 Tạo Khoa và Bộ môn...');

        $faculties = [
            [
                'ten_khoa' => 'Khoa Công nghệ Thông tin',
                'ma_khoa' => 'CNTT',
                'mo_ta' => 'Khoa Công nghệ Thông tin - Đào tạo các chuyên ngành về CNTT',
                'truong_khoa' => 'PGS.TS Nguyễn Văn A',
                'so_dien_thoai' => '028-1234567',
                'email' => 'cntt@university.edu.vn',
                'trang_thai' => 'active'
            ],
            [
                'ten_khoa' => 'Khoa Kinh tế',
                'ma_khoa' => 'KT',
                'mo_ta' => 'Khoa Kinh tế - Đào tạo các chuyên ngành về kinh tế',
                'truong_khoa' => 'TS Trần Thị B',
                'so_dien_thoai' => '028-1234568',
                'email' => 'kinhte@university.edu.vn',
                'trang_thai' => 'active'
            ],
            [
                'ten_khoa' => 'Khoa Ngoại ngữ',
                'ma_khoa' => 'NN',
                'mo_ta' => 'Khoa Ngoại ngữ - Đào tạo các chuyên ngành về ngôn ngữ',
                'truong_khoa' => 'TS Lê Văn C',
                'so_dien_thoai' => '028-1234569',
                'email' => 'ngoaingu@university.edu.vn',
                'trang_thai' => 'active'
            ]
        ];

        foreach ($faculties as $facultyData) {
            Faculty::firstOrCreate(
                ['ma_khoa' => $facultyData['ma_khoa']],
                $facultyData
            );
        }

        $departments = [
            [
                'ten_nganh' => 'Ngành Công nghệ Thông tin',
                'ma_nganh' => 'CNTT',
                'faculty_id' => Faculty::where('ma_khoa', 'CNTT')->first()->id,
                'mo_ta' => 'Ngành chuyên về công nghệ thông tin và phát triển phần mềm',
                'truong_nganh' => 'ThS Phạm Văn D',
                'trang_thai' => 'active'
            ],
            [
                'ten_nganh' => 'Ngành Mạng máy tính',
                'ma_nganh' => 'MMT',
                'faculty_id' => Faculty::where('ma_khoa', 'CNTT')->first()->id,
                'mo_ta' => 'Ngành chuyên về mạng máy tính và bảo mật',
                'truong_nganh' => 'ThS Hoàng Thị E',
                'trang_thai' => 'active'
            ],
            [
                'ten_nganh' => 'Ngành Kinh tế học',
                'ma_nganh' => 'KTH',
                'faculty_id' => Faculty::where('ma_khoa', 'KT')->first()->id,
                'mo_ta' => 'Ngành chuyên về kinh tế học cơ bản',
                'truong_nganh' => 'TS Nguyễn Văn F',
                'trang_thai' => 'active'
            ],
            [
                'ten_nganh' => 'Ngành Tiếng Anh',
                'ma_nganh' => 'TA',
                'faculty_id' => Faculty::where('ma_khoa', 'NN')->first()->id,
                'mo_ta' => 'Ngành chuyên về tiếng Anh',
                'truong_nganh' => 'ThS Trần Thị G',
                'trang_thai' => 'active'
            ]
        ];

        foreach ($departments as $deptData) {
            Department::firstOrCreate(
                ['ma_nganh' => $deptData['ma_nganh']],
                $deptData
            );
        }
    }

    /**
     * Tạo Admin Users
     */
    protected function createAdminUsers()
    {
        $this->command->info('👑 Tạo Admin Users...');

        $adminUsers = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@library.com',
                'password' => '123456',
                'role' => 'admin'
            ],
            [
                'name' => 'Nguyễn Văn Admin',
                'email' => 'admin2@library.com',
                'password' => '123456',
                'role' => 'admin'
            ],
            [
                'name' => 'Trần Thị Quản Lý',
                'email' => 'manager@library.com',
                'password' => '123456',
                'role' => 'admin'
            ]
        ];

        foreach ($adminUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role']
                ]
            );
        }
    }

    /**
     * Tạo Librarian Users
     */
    protected function createLibrarianUsers()
    {
        $this->command->info('📚 Tạo Librarian Users...');

        $librarianUsers = [
            [
                'name' => 'Lê Văn Thủ Thư',
                'email' => 'librarian@library.com',
                'password' => '123456',
                'role' => 'staff',
                'ho_ten' => 'Lê Văn Thủ Thư',
                'ma_thu_thu' => 'TT001',
                'so_dien_thoai' => '0123456789',
                'ngay_sinh' => '1985-05-15',
                'gioi_tinh' => 'male',
                'dia_chi' => '123 Đường ABC, Quận 1, TP.HCM',
                'chuc_vu' => 'Thủ thư trưởng',
                'phong_ban' => 'Phòng Quản lý Thư viện',
                'ngay_vao_lam' => '2020-01-15',
                'ngay_het_han_hop_dong' => '2025-01-15',
                'luong_co_ban' => 15000000,
                'trang_thai' => 'active',
                'bang_cap' => 'Đại học Thư viện',
                'kinh_nghiem' => '5 năm kinh nghiệm',
                'ghi_chu' => 'Thủ thư có kinh nghiệm lâu năm'
            ],
            [
                'name' => 'Phạm Thị Nhân Viên',
                'email' => 'staff@library.com',
                'password' => '123456',
                'role' => 'staff',
                'ho_ten' => 'Phạm Thị Nhân Viên',
                'ma_thu_thu' => 'TT002',
                'so_dien_thoai' => '0987654321',
                'ngay_sinh' => '1990-08-20',
                'gioi_tinh' => 'female',
                'dia_chi' => '456 Đường XYZ, Quận 2, TP.HCM',
                'chuc_vu' => 'Nhân viên thư viện',
                'phong_ban' => 'Phòng Phục vụ Độc giả',
                'ngay_vao_lam' => '2021-03-01',
                'ngay_het_han_hop_dong' => '2026-03-01',
                'luong_co_ban' => 12000000,
                'trang_thai' => 'active',
                'bang_cap' => 'Cao đẳng Thư viện',
                'kinh_nghiem' => '3 năm kinh nghiệm',
                'ghi_chu' => 'Nhân viên trẻ, năng động'
            ],
            [
                'name' => 'Hoàng Văn Trợ Lý',
                'email' => 'assistant@library.com',
                'password' => '123456',
                'role' => 'staff',
                'ho_ten' => 'Hoàng Văn Trợ Lý',
                'ma_thu_thu' => 'TT003',
                'so_dien_thoai' => '0369258147',
                'ngay_sinh' => '1992-12-10',
                'gioi_tinh' => 'male',
                'dia_chi' => '789 Đường DEF, Quận 3, TP.HCM',
                'chuc_vu' => 'Trợ lý thư viện',
                'phong_ban' => 'Phòng Kỹ thuật',
                'ngay_vao_lam' => '2022-06-15',
                'ngay_het_han_hop_dong' => '2027-06-15',
                'luong_co_ban' => 10000000,
                'trang_thai' => 'active',
                'bang_cap' => 'Trung cấp Thư viện',
                'kinh_nghiem' => '1 năm kinh nghiệm',
                'ghi_chu' => 'Trợ lý mới, đang học hỏi'
            ]
        ];

        foreach ($librarianUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role']
                ]
            );

            Librarian::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'ho_ten' => $data['ho_ten'],
                    'ma_thu_thu' => $data['ma_thu_thu'],
                    'email' => $data['email'],
                    'so_dien_thoai' => $data['so_dien_thoai'],
                    'ngay_sinh' => $data['ngay_sinh'],
                    'gioi_tinh' => $data['gioi_tinh'],
                    'dia_chi' => $data['dia_chi'],
                    'chuc_vu' => $data['chuc_vu'],
                    'phong_ban' => $data['phong_ban'],
                    'ngay_vao_lam' => $data['ngay_vao_lam'],
                    'ngay_het_han_hop_dong' => $data['ngay_het_han_hop_dong'],
                    'luong_co_ban' => $data['luong_co_ban'],
                    'trang_thai' => $data['trang_thai'],
                    'bang_cap' => $data['bang_cap'],
                    'kinh_nghiem' => $data['kinh_nghiem'],
                    'ghi_chu' => $data['ghi_chu']
                ]
            );
        }
    }

    /**
     * Tạo Reader Users
     */
    protected function createReaderUsers()
    {
        $this->command->info('👥 Tạo Reader Users...');

        $readerUsers = [
            [
                'name' => 'Nguyễn Văn Sinh Viên',
                'email' => 'student@library.com',
                'password' => '123456',
                'role' => 'user',
                'ho_ten' => 'Nguyễn Văn Sinh Viên',
                'so_dien_thoai' => '0123456789',
                'ngay_sinh' => '2000-01-15',
                'gioi_tinh' => 'Nam',
                'dia_chi' => '123 Đường Sinh Viên, Quận 1, TP.HCM',
                'so_the_doc_gia' => 'RD001',
                'ngay_cap_the' => now()->subDays(30),
                'ngay_het_han' => now()->addYear(),
                'trang_thai' => 'Hoat dong',
                'faculty_id' => Faculty::where('ma_khoa', 'CNTT')->first()->id,
                'department_id' => Department::where('ma_nganh', 'CNTT')->first()->id
            ],
            [
                'name' => 'Trần Thị Giảng Viên',
                'email' => 'teacher@library.com',
                'password' => '123456',
                'role' => 'user',
                'ho_ten' => 'Trần Thị Giảng Viên',
                'so_dien_thoai' => '0987654321',
                'ngay_sinh' => '1985-05-20',
                'gioi_tinh' => 'Nu',
                'dia_chi' => '456 Đường Giảng Viên, Quận 2, TP.HCM',
                'so_the_doc_gia' => 'RD002',
                'ngay_cap_the' => now()->subDays(60),
                'ngay_het_han' => now()->addYears(2),
                'trang_thai' => 'Hoat dong',
                'faculty_id' => Faculty::where('ma_khoa', 'CNTT')->first()->id,
                'department_id' => Department::where('ma_nganh', 'MMT')->first()->id
            ],
            [
                'name' => 'Lê Văn Nghiên Cứu',
                'email' => 'researcher@library.com',
                'password' => '123456',
                'role' => 'user',
                'ho_ten' => 'Lê Văn Nghiên Cứu',
                'so_dien_thoai' => '0369258147',
                'ngay_sinh' => '1980-08-10',
                'gioi_tinh' => 'Nam',
                'dia_chi' => '789 Đường Nghiên Cứu, Quận 3, TP.HCM',
                'so_the_doc_gia' => 'RD003',
                'ngay_cap_the' => now()->subDays(90),
                'ngay_het_han' => now()->addYears(3),
                'trang_thai' => 'Hoat dong',
                'faculty_id' => Faculty::where('ma_khoa', 'KT')->first()->id,
                'department_id' => Department::where('ma_nganh', 'KTH')->first()->id
            ],
            [
                'name' => 'Phạm Thị Học Viên',
                'email' => 'learner@library.com',
                'password' => '123456',
                'role' => 'user',
                'ho_ten' => 'Phạm Thị Học Viên',
                'so_dien_thoai' => '0741852963',
                'ngay_sinh' => '1995-12-25',
                'gioi_tinh' => 'Nu',
                'dia_chi' => '321 Đường Học Viên, Quận 4, TP.HCM',
                'so_the_doc_gia' => 'RD004',
                'ngay_cap_the' => now()->subDays(15),
                'ngay_het_han' => now()->addMonths(6),
                'trang_thai' => 'Hoat dong',
                'faculty_id' => Faculty::where('ma_khoa', 'NN')->first()->id,
                'department_id' => Department::where('ma_nganh', 'TA')->first()->id
            ],
            [
                'name' => 'Hoàng Văn Thạc Sĩ',
                'email' => 'master@library.com',
                'password' => '123456',
                'role' => 'user',
                'ho_ten' => 'Hoàng Văn Thạc Sĩ',
                'so_dien_thoai' => '0852741963',
                'ngay_sinh' => '1992-07-18',
                'gioi_tinh' => 'Nam',
                'dia_chi' => '654 Đường Thạc Sĩ, Quận 5, TP.HCM',
                'so_the_doc_gia' => 'RD005',
                'ngay_cap_the' => now()->subDays(45),
                'ngay_het_han' => now()->addYears(2),
                'trang_thai' => 'Hoat dong',
                'faculty_id' => Faculty::where('ma_khoa', 'CNTT')->first()->id,
                'department_id' => Department::where('ma_nganh', 'CNTT')->first()->id
            ],
            [
                'name' => 'Võ Thị Tiến Sĩ',
                'email' => 'doctor@library.com',
                'password' => '123456',
                'role' => 'user',
                'ho_ten' => 'Võ Thị Tiến Sĩ',
                'so_dien_thoai' => '0963852741',
                'ngay_sinh' => '1988-11-30',
                'gioi_tinh' => 'Nu',
                'dia_chi' => '987 Đường Tiến Sĩ, Quận 6, TP.HCM',
                'so_the_doc_gia' => 'RD006',
                'ngay_cap_the' => now()->subDays(120),
                'ngay_het_han' => now()->addYears(5),
                'trang_thai' => 'Hoat dong',
                'faculty_id' => Faculty::where('ma_khoa', 'KT')->first()->id,
                'department_id' => Department::where('ma_nganh', 'KTH')->first()->id
            ]
        ];

        foreach ($readerUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role']
                ]
            );

            Reader::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'ho_ten' => $data['ho_ten'],
                    'email' => $data['email'],
                    'so_dien_thoai' => $data['so_dien_thoai'],
                    'ngay_sinh' => $data['ngay_sinh'],
                    'gioi_tinh' => $data['gioi_tinh'],
                    'dia_chi' => $data['dia_chi'],
                    'so_the_doc_gia' => $data['so_the_doc_gia'],
                    'ngay_cap_the' => $data['ngay_cap_the'],
                    'ngay_het_han' => $data['ngay_het_han'],
                    'trang_thai' => $data['trang_thai'],
                    'faculty_id' => $data['faculty_id'],
                    'department_id' => $data['department_id']
                ]
            );
        }

        // Tạo thêm một số reader ngẫu nhiên
        $this->createRandomReaders();
    }

    /**
     * Tạo thêm một số reader ngẫu nhiên
     */
    protected function createRandomReaders()
    {
        $this->command->info('🎲 Tạo thêm Reader ngẫu nhiên...');

        $faculties = Faculty::all();
        $departments = Department::all();
        $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ'];
        $lastNames = ['Văn', 'Thị', 'Minh', 'Thanh', 'Hồng', 'Lan', 'Hương', 'Nam', 'Anh', 'Tuấn'];
        $middleNames = ['Văn', 'Thị', 'Minh', 'Thanh', 'Hồng', 'Lan', 'Hương', 'Nam', 'Anh', 'Tuấn'];

        for ($i = 1; $i <= 10; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $middleName = $middleNames[array_rand($middleNames)];
            $fullName = $firstName . ' ' . $middleName . ' ' . $lastName;
            
            $email = 'reader' . ($i + 6) . '@library.com';
            $phone = '0' . rand(100000000, 999999999);
            $cardNumber = 'RD' . str_pad($i + 6, 3, '0', STR_PAD_LEFT);
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $fullName,
                    'password' => Hash::make('123456'),
                    'role' => 'user'
                ]
            );

            Reader::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'ho_ten' => $fullName,
                    'email' => $email,
                    'so_dien_thoai' => $phone,
                    'ngay_sinh' => now()->subYears(rand(18, 50))->subDays(rand(1, 365)),
                    'gioi_tinh' => rand(0, 1) ? 'Nam' : 'Nu',
                    'dia_chi' => rand(1, 999) . ' Đường ' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5) . ', Quận ' . rand(1, 12) . ', TP.HCM',
                    'so_the_doc_gia' => $cardNumber,
                    'ngay_cap_the' => now()->subDays(rand(1, 365)),
                    'ngay_het_han' => now()->addDays(rand(30, 1095)),
                    'trang_thai' => rand(0, 1) ? 'Hoat dong' : 'Tam khoa',
                    'faculty_id' => $faculties->random()->id,
                    'department_id' => $departments->random()->id
                ]
            );
        }
    }
}