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
        
        $banners = [];
        for ($i = 1; $i <= 4; $i++) {
            $banners[$i] = [
                'number' => $i,
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
        
        return view('admin.banners.index', compact('banners'));
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
            
            // Xóa các file banner cũ với các định dạng khác nhau
            $extensions = ['jpg', 'jpeg', 'png', 'webp'];
            foreach ($extensions as $ext) {
                $oldFile = "{$bannerDir}/banner{$bannerNumber}.{$ext}";
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }
            
            // Lưu file mới
            $extension = $request->file('banner')->getClientOriginalExtension();
            $filename = "banner{$bannerNumber}.{$extension}";
            $request->file('banner')->move($bannerDir, $filename);
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', "Đã cập nhật banner {$bannerNumber} thành công!");
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa banner
     */
    public function delete($bannerNumber)
    {
        try {
            $bannerDir = public_path('storage/banners');
            $extensions = ['jpg', 'jpeg', 'png', 'webp'];
            $deleted = false;
            
            foreach ($extensions as $ext) {
                $filepath = "{$bannerDir}/banner{$bannerNumber}.{$ext}";
                if (File::exists($filepath)) {
                    File::delete($filepath);
                    $deleted = true;
                }
            }
            
            if ($deleted) {
                return redirect()
                    ->route('admin.banners.index')
                    ->with('success', "Đã xóa banner {$bannerNumber} thành công!");
            } else {
                return redirect()
                    ->route('admin.banners.index')
                    ->with('error', "Không tìm thấy banner {$bannerNumber}!");
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

