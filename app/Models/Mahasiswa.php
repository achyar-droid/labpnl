<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'kelas',
        'angkatan',
        'jenis_kelamin',
        'lab',
    ];
}

