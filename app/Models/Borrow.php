<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'reader_id',
        'book_id',
        'librarian_id',
        'ngay_muon',
        'ngay_hen_tra',
        'ngay_tra_thuc_te',
        'trang_thai',
        'so_lan_gia_han',
        'ngay_gia_han_cuoi',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_muon' => 'date',
        'ngay_hen_tra' => 'date',
        'ngay_tra_thuc_te' => 'date',
        'ngay_gia_han_cuoi' => 'date',
    ];

    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function librarian()
    {
        return $this->belongsTo(User::class, 'librarian_id');
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function pendingFines()
    {
        return $this->hasMany(Fine::class)->where('status', 'pending');
    }

    public function isOverdue()
    {
        return $this->trang_thai === 'Dang muon' && $this->ngay_hen_tra < now()->toDateString();
    }

    // Kiểm tra có thể gia hạn không
    public function canExtend()
    {
        $maxExtensions = 2; // Tối đa 2 lần gia hạn
        return $this->trang_thai === 'Dang muon' && 
               $this->so_lan_gia_han < $maxExtensions &&
               !$this->isOverdue();
    }

    // Gia hạn mượn sách
    public function extend($days = 7)
    {
        if (!$this->canExtend()) {
            return false;
        }

        $this->update([
            'ngay_hen_tra' => $this->ngay_hen_tra->addDays($days),
            'so_lan_gia_han' => $this->so_lan_gia_han + 1,
            'ngay_gia_han_cuoi' => now()->toDateString(),
        ]);

        return true;
    }

    // Tính số ngày quá hạn
    public function getDaysOverdueAttribute()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return now()->diffInDays($this->ngay_hen_tra);
    }

    // Kiểm tra có thể trả sách không
    public function canReturn()
    {
        return $this->trang_thai === 'Dang muon';
    }
}
