<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    /**
     * Hiển thị trang quản lý banner
     */
    public function index()
    {
        $bannerDir = public_path('storage/banners');
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        // Quản lý banner chính (banner carousel)
        $banners = [];
        for ($i = 1; $i <= 4; $i++) {
            $banners[$i] = [
                'number' => $i,
                'type' => 'banner',
                'title' => $this->getBannerTitle($i),
                'description' => $this->getBannerDescription($i),
                'path' => null,
                'exists' => false,
                'filename' => null,
                'size' => null,
                'updated_at' => null
            ];
            
            // Tìm file ảnh
            foreach ($extensions as $ext) {
                $filename = "banner{$i}.{$ext}";
                $filepath = $bannerDir . '/' . $filename;
                
                if (File::exists($filepath)) {
                    $banners[$i]['path'] = asset("storage/banners/{$filename}");
                    $banners[$i]['exists'] = true;
                    $banners[$i]['filename'] = $filename;
                    $banners[$i]['size'] = $this->formatBytes(File::size($filepath));
                    $banners[$i]['updated_at'] = date('d/m/Y H:i', File::lastModified($filepath));
                    break;
                }
            }
        }
        
        // Quản lý panel boxes
        $panels = [];
        $panelConfigs = [
            1 => ['title' => 'Tải xuống Danh mục SÁCH', 'description' => 'Panel 1 - Bên phải banner'],
            2 => ['title' => 'Thủ tục Hành chính Ngành xây dựng', 'description' => 'Panel 2 - Bên phải banner'],
            3 => ['title' => 'FREE Đọc sách miễn phí', 'description' => 'Panel 3 - Dưới banner'],
            'cooperation' => ['title' => 'LIÊN KẾT - HỢP TÁC XUẤT BẢN', 'description' => 'Banner hợp tác - Dưới banner']
        ];
        
        foreach ($panelConfigs as $key => $config) {
            $panelKey = is_numeric($key) ? "panel{$key}" : $key;
            $panels[$panelKey] = [
                'key' => $panelKey,
                'type' => 'panel',
                'title' => $config['title'],
                'description' => $config['description'],
                'path' => null,
                'exists' => false,
                'filename' => null,
                'size' => null,
                'updated_at' => null
            ];
            
            // Tìm file ảnh
            foreach ($extensions as $ext) {
                $filename = "{$panelKey}.{$ext}";
                $filepath = $bannerDir . '/' . $filename;
                
                if (File::exists($filepath)) {
                    $panels[$panelKey]['path'] = asset("storage/banners/{$filename}");
                    $panels[$panelKey]['exists'] = true;
                    $panels[$panelKey]['filename'] = $filename;
                    $panels[$panelKey]['size'] = $this->formatBytes(File::size($filepath));
                    $panels[$panelKey]['updated_at'] = date('d/m/Y H:i', File::lastModified($filepath));
                    break;
                }
            }
        }
        
        // Quản lý Service banners (Trân trọng phục vụ)
        $services = [];
        $serviceConfigs = [
            1 => ['title' => 'Bộ Xây dựng', 'description' => 'Service 1 - Trân trọng phục vụ'],
            2 => ['title' => 'Viện nghiên cứu', 'description' => 'Service 2 - Trân trọng phục vụ'],
            3 => ['title' => 'Doanh nghiệp/ Tổ chức', 'description' => 'Service 3 - Trân trọng phục vụ'],
            4 => ['title' => 'Nhà sách', 'description' => 'Service 4 - Trân trọng phục vụ'],
            5 => ['title' => 'Quản lý thư viện', 'description' => 'Service 5 - Trân trọng phục vụ'],
            6 => ['title' => 'Sinh viên', 'description' => 'Service 6 - Trân trọng phục vụ'],
            7 => ['title' => 'Tác giả', 'description' => 'Service 7 - Trân trọng phục vụ']
        ];
        
        foreach ($serviceConfigs as $key => $config) {
            $serviceKey = "service-{$key}";
            $services[$serviceKey] = [
                'key' => $serviceKey,
                'type' => 'service',
                'title' => $config['title'],
                'description' => $config['description'],
                'path' => null,
                'exists' => false,
                'filename' => null,
                'size' => null,
                'updated_at' => null
            ];
            
            foreach ($extensions as $ext) {
                $filename = "{$serviceKey}.{$ext}";
                $filepath = $bannerDir . '/' . $filename;
                
                if (File::exists($filepath)) {
                    $services[$serviceKey]['path'] = asset("storage/banners/{$filename}");
                    $services[$serviceKey]['exists'] = true;
                    $services[$serviceKey]['filename'] = $filename;
                    $services[$serviceKey]['size'] = $this->formatBytes(File::size($filepath));
                    $services[$serviceKey]['updated_at'] = date('d/m/Y H:i', File::lastModified($filepath));
                    break;
                }
            }
        }
        
        // Quản lý Author banners (Tác giả)
        $authors = [];
        for ($i = 1; $i <= 5; $i++) {
            $authorKey = "author-{$i}";
            $authors[$authorKey] = [
                'key' => $authorKey,
                'type' => 'author',
                'title' => "Tác giả {$i}",
                'description' => "Author banner {$i} - Phần Tác giả",
                'path' => null,
                'exists' => false,
                'filename' => null,
                'size' => null,
                'updated_at' => null
            ];
            
            foreach ($extensions as $ext) {
                $filename = "{$authorKey}.{$ext}";
                $filepath = $bannerDir . '/' . $filename;
                
                if (File::exists($filepath)) {
                    $authors[$authorKey]['path'] = asset("storage/banners/{$filename}");
                    $authors[$authorKey]['exists'] = true;
                    $authors[$authorKey]['filename'] = $filename;
                    $authors[$authorKey]['size'] = $this->formatBytes(File::size($filepath));
                    $authors[$authorKey]['updated_at'] = date('d/m/Y H:i', File::lastModified($filepath));
                    break;
                }
            }
        }
        
        // Quản lý Contact banners (Liên hệ - Hợp tác)
        $contacts = [];
        $contactConfigs = [
            1 => ['title' => 'Liên kết xuất bản', 'description' => 'Contact 1 - Liên hệ - Hợp tác'],
            2 => ['title' => 'Báo giá sách sỉ', 'description' => 'Contact 2 - Liên hệ - Hợp tác'],
            3 => ['title' => 'Dịch vụ in', 'description' => 'Contact 3 - Liên hệ - Hợp tác'],
            4 => ['title' => 'Liên hệ hỗ trợ', 'description' => 'Contact 4 - Liên hệ - Hợp tác']
        ];
        
        foreach ($contactConfigs as $key => $config) {
            $contactKey = "contact-{$key}";
            $contacts[$contactKey] = [
                'key' => $contactKey,
                'type' => 'contact',
                'title' => $config['title'],
                'description' => $config['description'],
                'path' => null,
                'exists' => false,
                'filename' => null,
                'size' => null,
                'updated_at' => null
            ];
            
            foreach ($extensions as $ext) {
                $filename = "{$contactKey}.{$ext}";
                $filepath = $bannerDir . '/' . $filename;
                
                if (File::exists($filepath)) {
                    $contacts[$contactKey]['path'] = asset("storage/banners/{$filename}");
                    $contacts[$contactKey]['exists'] = true;
                    $contacts[$contactKey]['filename'] = $filename;
                    $contacts[$contactKey]['size'] = $this->formatBytes(File::size($filepath));
                    $contacts[$contactKey]['updated_at'] = date('d/m/Y H:i', File::lastModified($filepath));
                    break;
                }
            }
        }
        
        // Quản lý News banners (Tin tức)
        $news = [];
        $newsConfigs = [
            'featured' => ['title' => 'Tin tức nổi bật', 'description' => 'News featured - Tin tức lớn bên trái'],
            1 => ['title' => 'Tin tức 1', 'description' => 'News 1 - Tin tức nhỏ bên phải'],
            2 => ['title' => 'Tin tức 2', 'description' => 'News 2 - Tin tức nhỏ bên phải'],
            3 => ['title' => 'Tin tức 3', 'description' => 'News 3 - Tin tức nhỏ bên phải']
        ];
        
        foreach ($newsConfigs as $key => $config) {
            $newsKey = is_numeric($key) ? "news-{$key}" : "news-{$key}";
            $news[$newsKey] = [
                'key' => $newsKey,
                'type' => 'news',
                'title' => $config['title'],
                'description' => $config['description'],
                'path' => null,
                'exists' => false,
                'filename' => null,
                'size' => null,
                'updated_at' => null
            ];
            
            foreach ($extensions as $ext) {
                $filename = "{$newsKey}.{$ext}";
                $filepath = $bannerDir . '/' . $filename;
                
                if (File::exists($filepath)) {
                    $news[$newsKey]['path'] = asset("storage/banners/{$filename}");
                    $news[$newsKey]['exists'] = true;
                    $news[$newsKey]['filename'] = $filename;
                    $news[$newsKey]['size'] = $this->formatBytes(File::size($filepath));
                    $news[$newsKey]['updated_at'] = date('d/m/Y H:i', File::lastModified($filepath));
                    break;
                }
            }
        }
        
        // Quản lý Diem Sach banners (Điểm sách)
        $diemSach = [];
        $diemSachConfigs = [
            'featured' => ['title' => 'Điểm sách nổi bật', 'description' => 'Diem Sach featured - Sách lớn bên trái'],
            1 => ['title' => 'Điểm sách 1', 'description' => 'Diem Sach 1 - Sách nhỏ bên phải'],
            2 => ['title' => 'Điểm sách 2', 'description' => 'Diem Sach 2 - Sách nhỏ bên phải'],
            3 => ['title' => 'Điểm sách 3', 'description' => 'Diem Sach 3 - Sách nhỏ bên phải']
        ];
        
        foreach ($diemSachConfigs as $key => $config) {
            $diemSachKey = is_numeric($key) ? "diem-sach-{$key}" : "diem-sach-{$key}";
            $diemSach[$diemSachKey] = [
                'key' => $diemSachKey,
                'type' => 'diem-sach',
                'title' => $config['title'],
                'description' => $config['description'],
                'path' => null,
                'exists' => false,
                'filename' => null,
                'size' => null,
                'updated_at' => null
            ];
            
            foreach ($extensions as $ext) {
                $filename = "{$diemSachKey}.{$ext}";
                $filepath = $bannerDir . '/' . $filename;
                
                if (File::exists($filepath)) {
                    $diemSach[$diemSachKey]['path'] = asset("storage/banners/{$filename}");
                    $diemSach[$diemSachKey]['exists'] = true;
                    $diemSach[$diemSachKey]['filename'] = $filename;
                    $diemSach[$diemSachKey]['size'] = $this->formatBytes(File::size($filepath));
                    $diemSach[$diemSachKey]['updated_at'] = date('d/m/Y H:i', File::lastModified($filepath));
                    break;
                }
            }
        }
        
        return view('admin.banners.index', compact('banners', 'panels', 'services', 'authors', 'contacts', 'news', 'diemSach'));
    }
    
    /**
     * Upload/Update banner
     */
    public function upload(Request $request, $bannerNumber)
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048'
        ], [
            'banner.required' => 'Vui lòng chọn ảnh banner',
            'banner.image' => 'File phải là ảnh',
            'banner.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png, hoặc webp',
            'banner.max' => 'Kích thước ảnh tối đa là 2MB'
        ]);
        
        try {
            $bannerDir = public_path('storage/banners');
            
            // Đảm bảo thư mục tồn tại
            if (!File::exists($bannerDir)) {
                File::makeDirectory($bannerDir, 0755, true);
            }
            
            // Xác định loại banner
            $type = $request->input('type', 'banner');
            
            // Xóa các file banner cũ với các định dạng khác nhau
            $extensions = ['jpg', 'jpeg', 'png', 'webp'];
            foreach ($extensions as $ext) {
                $oldFile = null;
                if ($type === 'panel') {
                    $oldFile = "{$bannerDir}/{$bannerNumber}.{$ext}";
                } elseif ($type === 'service') {
                    $oldFile = "{$bannerDir}/service-{$bannerNumber}.{$ext}";
                } elseif ($type === 'author') {
                    $oldFile = "{$bannerDir}/author-{$bannerNumber}.{$ext}";
                } elseif ($type === 'contact') {
                    $oldFile = "{$bannerDir}/contact-{$bannerNumber}.{$ext}";
                } elseif ($type === 'news') {
                    $oldFile = "{$bannerDir}/news-{$bannerNumber}.{$ext}";
                } elseif ($type === 'diem-sach') {
                    $oldFile = "{$bannerDir}/diem-sach-{$bannerNumber}.{$ext}";
                } else {
                    $oldFile = "{$bannerDir}/banner{$bannerNumber}.{$ext}";
                }
                
                if ($oldFile && File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }
            
            // Lưu file mới
            $extension = $request->file('banner')->getClientOriginalExtension();
            if ($type === 'panel') {
                $filename = "{$bannerNumber}.{$extension}";
                $message = "Đã cập nhật panel {$bannerNumber} thành công!";
            } elseif ($type === 'service') {
                $filename = "service-{$bannerNumber}.{$extension}";
                $message = "Đã cập nhật service banner {$bannerNumber} thành công!";
            } elseif ($type === 'author') {
                $filename = "author-{$bannerNumber}.{$extension}";
                $message = "Đã cập nhật author banner {$bannerNumber} thành công!";
            } elseif ($type === 'contact') {
                $filename = "contact-{$bannerNumber}.{$extension}";
                $message = "Đã cập nhật contact banner {$bannerNumber} thành công!";
            } elseif ($type === 'news') {
                $filename = "news-{$bannerNumber}.{$extension}";
                $message = "Đã cập nhật news banner {$bannerNumber} thành công!";
            } elseif ($type === 'diem-sach') {
                $filename = "diem-sach-{$bannerNumber}.{$extension}";
                $message = "Đã cập nhật diem sach banner {$bannerNumber} thành công!";
            } else {
                $filename = "banner{$bannerNumber}.{$extension}";
                $message = "Đã cập nhật banner {$bannerNumber} thành công!";
            }
            $request->file('banner')->move($bannerDir, $filename);
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa banner
     */
    public function delete(Request $request, $bannerNumber)
    {
        try {
            $bannerDir = public_path('storage/banners');
            $extensions = ['jpg', 'jpeg', 'png', 'webp'];
            $deleted = false;
            
            // Xác định loại banner
            $type = $request->input('type', 'banner');
            
            foreach ($extensions as $ext) {
                $filepath = null;
                if ($type === 'panel') {
                    $filepath = "{$bannerDir}/{$bannerNumber}.{$ext}";
                    $message = "Đã xóa panel {$bannerNumber} thành công!";
                    $errorMessage = "Không tìm thấy panel {$bannerNumber}!";
                } elseif ($type === 'service') {
                    $filepath = "{$bannerDir}/service-{$bannerNumber}.{$ext}";
                    $message = "Đã xóa service banner {$bannerNumber} thành công!";
                    $errorMessage = "Không tìm thấy service banner {$bannerNumber}!";
                } elseif ($type === 'author') {
                    $filepath = "{$bannerDir}/author-{$bannerNumber}.{$ext}";
                    $message = "Đã xóa author banner {$bannerNumber} thành công!";
                    $errorMessage = "Không tìm thấy author banner {$bannerNumber}!";
                } elseif ($type === 'contact') {
                    $filepath = "{$bannerDir}/contact-{$bannerNumber}.{$ext}";
                    $message = "Đã xóa contact banner {$bannerNumber} thành công!";
                    $errorMessage = "Không tìm thấy contact banner {$bannerNumber}!";
                } elseif ($type === 'news') {
                    $filepath = "{$bannerDir}/news-{$bannerNumber}.{$ext}";
                    $message = "Đã xóa news banner {$bannerNumber} thành công!";
                    $errorMessage = "Không tìm thấy news banner {$bannerNumber}!";
                } elseif ($type === 'diem-sach') {
                    $filepath = "{$bannerDir}/diem-sach-{$bannerNumber}.{$ext}";
                    $message = "Đã xóa diem sach banner {$bannerNumber} thành công!";
                    $errorMessage = "Không tìm thấy diem sach banner {$bannerNumber}!";
                } else {
                    $filepath = "{$bannerDir}/banner{$bannerNumber}.{$ext}";
                    $message = "Đã xóa banner {$bannerNumber} thành công!";
                    $errorMessage = "Không tìm thấy banner {$bannerNumber}!";
                }
                
                if ($filepath && File::exists($filepath)) {
                    File::delete($filepath);
                    $deleted = true;
                }
            }
            
            if ($deleted) {
                return redirect()
                    ->route('admin.banners.index')
                    ->with('success', $message);
            } else {
                return redirect()
                    ->route('admin.banners.index')
                    ->with('error', $errorMessage);
            }
            
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Lấy tiêu đề banner
     */
    private function getBannerTitle($number)
    {
        $titles = [
            1 => 'MUA 1 NĂM TẶNG 1 TÚI CANVAS',
            2 => 'ĐỌC SÁCH KHÔNG GIỚI HẠN',
            3 => 'SÁCH NÓI MIỄN PHÍ',
            4 => 'TRUYỆN TRANH HOT NHẤT'
        ];
        
        return $titles[$number] ?? "Banner {$number}";
    }
    
    /**
     * Lấy mô tả banner
     */
    private function getBannerDescription($number)
    {
        $descriptions = [
            1 => 'Chỉ 99K - Áp dụng cho mọi khách hàng',
            2 => 'Hàng ngàn đầu sách chỉ với 99K/tháng',
            3 => 'Nghe sách mọi lúc mọi nơi - Hoàn toàn miễn phí',
            4 => 'Cập nhật mỗi ngày - Đọc trọn bộ không quảng cáo'
        ];
        
        return $descriptions[$number] ?? '';
    }
    
    /**
     * Format bytes thành đơn vị dễ đọc
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}



