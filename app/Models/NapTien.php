<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NapTien extends Model
{
 protected $table = 'nap_tien';
    protected $fillable = ['reader_id', 'so_tien', 'trang_thai', 'ma_giao_dich'];

    public function reader()
    {
        return $this->belongsTo(Reader::class, 'reader_id');
    }
}
