<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'no_laporan',
        'judul',
        'deskripsi',
        'tanggal',
        'phone',
        'status_laporan',
        'photo',
        'lokasi',
        'user_update',
    ];
}
