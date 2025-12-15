<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Inventory;
use Carbon\Carbon;

class PricingService
{
    /**
     * Tính phí thuê dựa trên giá sách và số ngày mượn
     * 
     * @param float $bookPrice Giá sách
     * @param int $days Số ngày mượn
     * @param bool $hasCard Có thẻ độc giả hay không
     * @return float Phí thuê
     */
    public static function calculateRentalFee($bookPrice, $days, $hasCard = false)
    {
        // Tỷ lệ phí thuê mỗi ngày (1% giá sách mỗi ngày)
        // Có thể điều chỉnh tỷ lệ này trong config
        $dailyRate = config('library.rental_daily_rate', 0.01); // 1% mỗi ngày
        
        // Nếu có thẻ độc giả, có thể giảm tỷ lệ
        if ($hasCard) {
            $dailyRate = config('library.rental_daily_rate_with_card', 0.005); // 0.5% mỗi ngày
        }
        
        // Tính phí thuê = giá sách * tỷ lệ mỗi ngày * số ngày
        $rentalFee = $bookPrice * $dailyRate * $days;
        
        // Làm tròn đến hàng nghìn
        return round($rentalFee / 1000) * 1000;
    }

    /**
     * Tính tiền cọc dựa trên giá sách và tình trạng sách
     * 
     * @param float $bookPrice Giá sách
     * @param string $condition Tình trạng sách (Moi, Tot, Trung binh, Cu)
     * @param string $bookType Loại sách (quy, binh_thuong, tham_khao)
     * @param bool $hasCard Có thẻ độc giả hay không
     * @return float Tiền cọc
     */
    public static function calculateDeposit($bookPrice, $condition, $bookType = 'binh_thuong', $hasCard = false)
    {
        // Nếu sách quý, tiền cọc = 100% giá sách
        if ($bookType === 'quy') {
            if (in_array($condition, ['Moi', 'Tot'])) {
                return $bookPrice;
            } elseif ($condition === 'Trung binh') {
                return round($bookPrice * 0.7);
            } elseif ($condition === 'Cu') {
                return round($bookPrice * 0.6);
            }
            return $bookPrice;
        }

        // Sách bình thường hoặc tham khảo
        if ($hasCard) {
            // Có thẻ độc giả
            switch ($condition) {
                case 'Moi':
                    return round($bookPrice * 0.4);
                case 'Tot':
                    return round($bookPrice * 0.3);
                case 'Trung binh':
                    return round($bookPrice * 0.2);
                case 'Cu':
                    return round($bookPrice * 0.15);
                default:
                    return round($bookPrice * 0.2);
            }
        } else {
            // Không có thẻ độc giả
            switch ($condition) {
                case 'Moi':
                    return round($bookPrice * 0.4);
                case 'Tot':
                    return round($bookPrice * 0.3);
                case 'Trung binh':
                    return round($bookPrice * 0.3);
                case 'Cu':
                    return round($bookPrice * 0.2);
                default:
                    return round($bookPrice * 0.2);
            }
        }
    }

    /**
     * Tính phí thuê và tiền cọc cho một BorrowItem
     * 
     * @param Book $book
     * @param Inventory $inventory
     * @param Carbon $ngayMuon
     * @param Carbon $ngayHenTra
     * @param bool $hasCard
     * @return array ['tien_thue' => float, 'tien_coc' => float]
     */
    public static function calculateFees(Book $book, Inventory $inventory, $ngayMuon, $ngayHenTra, $hasCard = false)
    {
        $bookPrice = floatval($book->gia ?? 0);
        $condition = $inventory->condition ?? 'Trung binh';
        $bookType = $book->loai_sach ?? 'binh_thuong';
        
        // Tính số ngày mượn
        $days = 1;
        if ($ngayMuon && $ngayHenTra) {
            $ngayMuonCarbon = Carbon::parse($ngayMuon);
            $ngayHenTraCarbon = Carbon::parse($ngayHenTra);
            $days = max(1, $ngayMuonCarbon->diffInDays($ngayHenTraCarbon));
        }
        
        // Nếu inventory không hợp lệ, trả về 0
        if (in_array($inventory->status, ['Hong', 'Dang muon', 'Mat'])) {
            return [
                'tien_thue' => 0,
                'tien_coc' => 0
            ];
        }
        
        // Tính phí thuê
        $tienThue = self::calculateRentalFee($bookPrice, $days, $hasCard);
        
        // Tính tiền cọc
        $tienCoc = self::calculateDeposit($bookPrice, $condition, $bookType, $hasCard);
        
        // Nếu sách quý và có thẻ, không tính phí thuê
        if ($bookType === 'quy' && $hasCard) {
            $tienThue = 0;
        }
        
        return [
            'tien_thue' => $tienThue,
            'tien_coc' => $tienCoc
        ];
    }
}

