<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    protected $lab;

    public function __construct($lab)
    {
        $this->lab = $lab;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // VALIDASI FORMAT WAJIB
            if (
                !isset(
                    $row['nim'],
                    $row['nama_mahasiswa'],
                    $row['prodi'],
                    $row['kelas'],
                    $row['angkatan'],
                    $row['jenis_kelamin']
                )
            ) {
                throw new \Exception('FORMAT_EXCEL_SALAH');
            }

            Mahasiswa::updateOrCreate(
                ['nim' => $row['nim']],
                [
                    'nama'          => $row['nama_mahasiswa'],
                    'prodi'         => strtoupper($row['prodi']),
                    'kelas'         => strtoupper($row['kelas']),
                    'angkatan'      => $row['angkatan'],
                    'jenis_kelamin' => strtoupper($row['jenis_kelamin']),
                    'lab'           => $this->lab,
                ]
            );
        }
    }
}
