<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'published_date',
        'link_url',
        'image',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];
}
