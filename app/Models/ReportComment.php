<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'laporan_id',
        'user_id',
        'tanggal',
        'deskripsi',
        'reff',
    ];
}
