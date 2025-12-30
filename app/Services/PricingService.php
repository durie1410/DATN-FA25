<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Inventory;
use Carbon\Carbon;

class PricingService
{
    /**
     * Tính phí thuê dựa trên giá sách và số ngày mượn
     * Tính phí thuê cho tất cả sách (không phụ thuộc vào condition)
     * 
     * @param float $bookPrice Giá sách
     * @param int $days Số ngày mượn
     * @param string $condition Tình trạng sách (Moi, Tot, Trung binh, Cu)
     * @return float Phí thuê
     */
    public static function calculateRentalFee($bookPrice, $days, $condition = 'Trung binh')
    {
        // Kiểm tra giá sách hợp lệ
        if ($bookPrice <= 0 || $days <= 0) {
            return 0;
        }
        
        // Tỷ lệ phí thuê mỗi ngày (1% giá sách mỗi ngày)
        // Không phân biệt có thẻ độc giả hay không
        // Tính phí thuê cho tất cả sách, không phân biệt tình trạng (theo config: only_for_new_books = false)
        $dailyRate = config('pricing.rental.daily_rate', config('library.rental_daily_rate', 0.01)); // 1% mỗi ngày
        
        // Tính phí thuê = giá sách * tỷ lệ mỗi ngày * số ngày
        $rentalFee = $bookPrice * $dailyRate * $days;
        
        // Làm tròn theo cấu hình (mặc định làm tròn đến hàng nghìn)
        $roundTo = config('pricing.rental.round_to', 1000);
        return round($rentalFee / $roundTo) * $roundTo;
    }

    /**
     * Tính tiền cọc dựa trên giá sách
     * Tiền cọc = giá sách (1:1)
     * 
     * @param float $bookPrice Giá sách
     * @param string $condition Tình trạng sách (Moi, Tot, Trung binh, Cu)
     * @param string $bookType Loại sách (quy, binh_thuong, tham_khao)
     * @param bool $hasCard Có thẻ độc giả hay không
     * @return float Tiền cọc
     */
    public static function calculateDeposit($bookPrice, $condition, $bookType = 'binh_thuong', $hasCard = false)
    {
        // Tiền cọc = giá sách (1:1)
        return $bookPrice;
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
        
        // Tính phí thuê (tính cho tất cả sách)
        $tienThue = self::calculateRentalFee($bookPrice, $days, $condition);
        
        // Tính tiền cọc
        $tienCoc = self::calculateDeposit($bookPrice, $condition, $bookType, $hasCard);
        
        return [
            'tien_thue' => $tienThue,
            'tien_coc' => $tienCoc
        ];
    }

    /**
     * Tính phí trả muộn
     * 
     * @param Carbon $dueDate Ngày hẹn trả
     * @param Carbon|null $returnDate Ngày trả thực tế (null nếu chưa trả)
     * @return float Phí trả muộn
     */
    public static function calculateLateReturnFine($dueDate, $returnDate = null)
    {
        $dueDateCarbon = Carbon::parse($dueDate);
        $returnDateCarbon = $returnDate ? Carbon::parse($returnDate) : Carbon::now();
        
        // Tính số ngày quá hạn
        $daysOverdue = max(0, $dueDateCarbon->diffInDays($returnDateCarbon, false));
        
        if ($daysOverdue <= 0) {
            return 0;
        }
        
        // Lấy tỷ lệ phí trả muộn từ config
        $dailyRate = config('pricing.fines.late_return.daily_rate', 5000);
        
        // Tính phí = số ngày quá hạn × phí mỗi ngày
        $fineAmount = $daysOverdue * $dailyRate;
        
        return round($fineAmount);
    }

    /**
     * Tính phí làm hỏng sách
     * 
     * @param float $bookPrice Giá sách
     * @param string $bookType Loại sách (quy, binh_thuong, tham_khao)
     * @param string $condition Tình trạng sách khi mượn (Moi, Tot, Trung binh, Cu, Hong)
     * @return float Phí làm hỏng
     */
    public static function calculateDamagedBookFine($bookPrice, $bookType, $condition)
    {
        if ($bookPrice <= 0) {
            return 0;
        }
        
        $fineConfig = config('pricing.fines.damaged_book.by_book_type', []);
        
        // Xử lý sách quý
        if ($bookType === 'quy') {
            $rate = $fineConfig['quy']['rate'] ?? 1.0;
            return round($bookPrice * $rate);
        }
        
        // Xử lý sách bình thường và tham khảo
        $typeConfig = $fineConfig[$bookType] ?? $fineConfig['binh_thuong'] ?? null;
        
        if (!$typeConfig || !isset($typeConfig['by_condition'])) {
            // Mặc định 70% nếu không có config
            return round($bookPrice * 0.7);
        }
        
        $rate = $typeConfig['by_condition'][$condition] ?? 0.7;
        return round($bookPrice * $rate);
    }

    /**
     * Tính phí mất sách
     * 
     * @param float $bookPrice Giá sách
     * @param string $bookType Loại sách (quy, binh_thuong, tham_khao)
     * @param string $condition Tình trạng sách khi mượn (Moi, Tot, Trung binh, Cu, Hong)
     * @return float Phí mất sách
     */
    public static function calculateLostBookFine($bookPrice, $bookType, $condition)
    {
        // Phí mất sách tính giống như phí làm hỏng
        return self::calculateDamagedBookFine($bookPrice, $bookType, $condition);
    }
}

