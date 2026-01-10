<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPeminjaman extends Model
{
    protected $table = 'laporan_peminjaman';

    protected $fillable = [
        'mahasiswa_id',
        'barang_id',
        'lab',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
