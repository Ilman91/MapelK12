<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktor extends Model
{
    protected $fillable = [
        'nama_aktor',
        'gender',
        'umur',
        'foto',
    ];
    public $timestamps = true;
}
