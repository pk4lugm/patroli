<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_awal',
        'jam_akhir',
        'penugasan',
    ];
}
