<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $fillable = [
        'judul',
        'durasi',
        'rating',
        'deskripsi',
        'tahun_rilis',
        'poster',
        'id_genre',
        'sutradara',
    ];
    public $timestamps = true;
}
