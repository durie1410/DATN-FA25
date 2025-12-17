<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Faculty;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lấy các khoa đã tạo
        $cnttFaculty = Faculty::where('ma_khoa', 'CNTT')->first();
        $ktFaculty = Faculty::where('ma_khoa', 'KT')->first();
        $nnFaculty = Faculty::where('ma_khoa', 'NN')->first();
        $luatFaculty = Faculty::where('ma_khoa', 'LUAT')->first();
        $spFaculty = Faculty::where('ma_khoa', 'SP')->first();
        $khtnFaculty = Faculty::where('ma_khoa', 'KHTN')->first();
        $khxhnvFaculty = Faculty::where('ma_khoa', 'KHXHNV')->first();

        $departments = [
            // Khoa Công nghệ Thông tin
            [
                'ten_nganh' => 'Khoa học Máy tính',
                'ma_nganh' => 'KHM',
                'faculty_id' => $cnttFaculty->id,
                'mo_ta' => 'Ngành chuyên về lý thuyết và ứng dụng của khoa học máy tính',
                'truong_nganh' => 'TS. Nguyễn Văn H',
                'so_dien_thoai' => '024 3754 2001',
                'email' => 'khm@vnu.edu.vn',
                'dia_chi' => 'Phòng 201, Tầng 2, Nhà E1',
                'website' => 'https://khm.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Công nghệ Thông tin',
                'ma_nganh' => 'CNTT',
                'faculty_id' => $cnttFaculty->id,
                'mo_ta' => 'Ngành chuyên về phát triển phần mềm và hệ thống thông tin',
                'truong_nganh' => 'PGS.TS. Trần Thị I',
                'so_dien_thoai' => '024 3754 2002',
                'email' => 'cntt@vnu.edu.vn',
                'dia_chi' => 'Phòng 202, Tầng 2, Nhà E1',
                'website' => 'https://cntt.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'An toàn Thông tin',
                'ma_nganh' => 'ATT',
                'faculty_id' => $cnttFaculty->id,
                'mo_ta' => 'Ngành chuyên về bảo mật và an toàn thông tin',
                'truong_nganh' => 'TS. Lê Văn J',
                'so_dien_thoai' => '024 3754 2003',
                'email' => 'att@vnu.edu.vn',
                'dia_chi' => 'Phòng 203, Tầng 2, Nhà E1',
                'website' => 'https://att.vnu.edu.vn',
                'ngay_thanh_lap' => '2010-01-01',
                'trang_thai' => 'active',
            ],

            // Khoa Kinh tế
            [
                'ten_nganh' => 'Kinh tế học',
                'ma_nganh' => 'KTH',
                'faculty_id' => $ktFaculty->id,
                'mo_ta' => 'Ngành chuyên về lý thuyết kinh tế và phân tích kinh tế',
                'truong_nganh' => 'GS.TS. Phạm Thị K',
                'so_dien_thoai' => '024 3754 3001',
                'email' => 'kth@vnu.edu.vn',
                'dia_chi' => 'Phòng 301, Tầng 3, Nhà E1',
                'website' => 'https://kth.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Quản trị Kinh doanh',
                'ma_nganh' => 'QTKD',
                'faculty_id' => $ktFaculty->id,
                'mo_ta' => 'Ngành chuyên về quản lý và điều hành doanh nghiệp',
                'truong_nganh' => 'PGS.TS. Hoàng Văn L',
                'so_dien_thoai' => '024 3754 3002',
                'email' => 'qtkd@vnu.edu.vn',
                'dia_chi' => 'Phòng 302, Tầng 3, Nhà E1',
                'website' => 'https://qtkd.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Tài chính - Ngân hàng',
                'ma_nganh' => 'TCNH',
                'faculty_id' => $ktFaculty->id,
                'mo_ta' => 'Ngành chuyên về tài chính và hoạt động ngân hàng',
                'truong_nganh' => 'TS. Vũ Thị M',
                'so_dien_thoai' => '024 3754 3003',
                'email' => 'tcnh@vnu.edu.vn',
                'dia_chi' => 'Phòng 303, Tầng 3, Nhà E1',
                'website' => 'https://tcnh.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],

            // Khoa Ngoại ngữ
            [
                'ten_nganh' => 'Tiếng Anh',
                'ma_nganh' => 'TA',
                'faculty_id' => $nnFaculty->id,
                'mo_ta' => 'Ngành chuyên về ngôn ngữ và văn hóa Anh',
                'truong_nganh' => 'TS. Đặng Văn N',
                'so_dien_thoai' => '024 3754 4001',
                'email' => 'ta@vnu.edu.vn',
                'dia_chi' => 'Phòng 401, Tầng 4, Nhà E1',
                'website' => 'https://ta.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Tiếng Pháp',
                'ma_nganh' => 'TP',
                'faculty_id' => $nnFaculty->id,
                'mo_ta' => 'Ngành chuyên về ngôn ngữ và văn hóa Pháp',
                'truong_nganh' => 'PGS.TS. Bùi Thị O',
                'so_dien_thoai' => '024 3754 4002',
                'email' => 'tp@vnu.edu.vn',
                'dia_chi' => 'Phòng 402, Tầng 4, Nhà E1',
                'website' => 'https://tp.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Tiếng Trung',
                'ma_nganh' => 'TT',
                'faculty_id' => $nnFaculty->id,
                'mo_ta' => 'Ngành chuyên về ngôn ngữ và văn hóa Trung Quốc',
                'truong_nganh' => 'TS. Ngô Văn P',
                'so_dien_thoai' => '024 3754 4003',
                'email' => 'tt@vnu.edu.vn',
                'dia_chi' => 'Phòng 403, Tầng 4, Nhà E1',
                'website' => 'https://tt.vnu.edu.vn',
                'ngay_thanh_lap' => '2000-01-01',
                'trang_thai' => 'active',
            ],

            // Khoa Luật
            [
                'ten_nganh' => 'Luật Dân sự',
                'ma_nganh' => 'LDS',
                'faculty_id' => $luatFaculty->id,
                'mo_ta' => 'Ngành chuyên về luật dân sự và thương mại',
                'truong_nganh' => 'PGS.TS. Phan Thị Q',
                'so_dien_thoai' => '024 3754 5001',
                'email' => 'lds@vnu.edu.vn',
                'dia_chi' => 'Phòng 501, Tầng 5, Nhà E1',
                'website' => 'https://lds.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Luật Hình sự',
                'ma_nganh' => 'LHS',
                'faculty_id' => $luatFaculty->id,
                'mo_ta' => 'Ngành chuyên về luật hình sự và tố tụng',
                'truong_nganh' => 'GS.TS. Võ Văn R',
                'so_dien_thoai' => '024 3754 5002',
                'email' => 'lhs@vnu.edu.vn',
                'dia_chi' => 'Phòng 502, Tầng 5, Nhà E1',
                'website' => 'https://lhs.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],

            // Khoa Sư phạm
            [
                'ten_nganh' => 'Sư phạm Toán',
                'ma_nganh' => 'SPT',
                'faculty_id' => $spFaculty->id,
                'mo_ta' => 'Ngành chuyên về giảng dạy toán học',
                'truong_nganh' => 'PGS.TS. Trịnh Thị S',
                'so_dien_thoai' => '024 3754 6001',
                'email' => 'spt@vnu.edu.vn',
                'dia_chi' => 'Phòng 601, Tầng 6, Nhà E1',
                'website' => 'https://spt.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Sư phạm Vật lý',
                'ma_nganh' => 'SPVL',
                'faculty_id' => $spFaculty->id,
                'mo_ta' => 'Ngành chuyên về giảng dạy vật lý',
                'truong_nganh' => 'TS. Đinh Văn T',
                'so_dien_thoai' => '024 3754 6002',
                'email' => 'spvl@vnu.edu.vn',
                'dia_chi' => 'Phòng 602, Tầng 6, Nhà E1',
                'website' => 'https://spvl.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],

            // Khoa Khoa học Tự nhiên
            [
                'ten_nganh' => 'Toán học',
                'ma_nganh' => 'TH',
                'faculty_id' => $khtnFaculty->id,
                'mo_ta' => 'Ngành chuyên về toán học và ứng dụng',
                'truong_nganh' => 'GS.TS. Lý Thị U',
                'so_dien_thoai' => '024 3754 7001',
                'email' => 'th@vnu.edu.vn',
                'dia_chi' => 'Phòng 701, Tầng 7, Nhà E1',
                'website' => 'https://th.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Vật lý',
                'ma_nganh' => 'VL',
                'faculty_id' => $khtnFaculty->id,
                'mo_ta' => 'Ngành chuyên về vật lý học và ứng dụng',
                'truong_nganh' => 'PGS.TS. Hồ Văn V',
                'so_dien_thoai' => '024 3754 7002',
                'email' => 'vl@vnu.edu.vn',
                'dia_chi' => 'Phòng 702, Tầng 7, Nhà E1',
                'website' => 'https://vl.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],

            // Khoa Khoa học Xã hội và Nhân văn
            [
                'ten_nganh' => 'Lịch sử',
                'ma_nganh' => 'LS',
                'faculty_id' => $khxhnvFaculty->id,
                'mo_ta' => 'Ngành chuyên về lịch sử và nghiên cứu lịch sử',
                'truong_nganh' => 'GS.TS. Nguyễn Thị W',
                'so_dien_thoai' => '024 3754 8001',
                'email' => 'ls@vnu.edu.vn',
                'dia_chi' => 'Phòng 801, Tầng 8, Nhà E1',
                'website' => 'https://ls.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nganh' => 'Văn học',
                'ma_nganh' => 'VH',
                'faculty_id' => $khxhnvFaculty->id,
                'mo_ta' => 'Ngành chuyên về văn học và nghiên cứu văn học',
                'truong_nganh' => 'PGS.TS. Trần Văn X',
                'so_dien_thoai' => '024 3754 8002',
                'email' => 'vh@vnu.edu.vn',
                'dia_chi' => 'Phòng 802, Tầng 8, Nhà E1',
                'website' => 'https://vh.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
