<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MahasiswaImport;


class MahasiswaController extends Controller
{
    public function index(Request $request, $lab)
{
    // cek apakah benar-benar ada filter
    $hasFilter = $request->filled('prodi')
        || $request->filled('kelas')
        || $request->filled('angkatan')
        || ($request->filled('q') && trim($request->q) !== '');

    $mahasiswas = collect();

    if ($hasFilter) {
        $query = Mahasiswa::query();

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        if ($request->filled('q') && trim($request->q) !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('nim', 'like', "%{$request->q}%")
                  ->orWhere('nama', 'like', "%{$request->q}%");
            });
        }

        $mahasiswas = $query->orderBy('nim')->get();
    }

    return view('dashboard.mahasiswa', [
        'lab'         => $lab,
        'mahasiswas'  => $mahasiswas,
        'prodi'       => $request->prodi,
        'kelas'       => $request->kelas,
        'angkatan'    => $request->angkatan,
        'q'           => $request->q,
        'isFiltered'  => $hasFilter,
    ]);
}

    public function store(Request $request, $lab)
    {
        $request->validate([
            'nim'           => 'required|unique:mahasiswas,nim',
            'nama'          => 'required',
            'prodi'         => 'required|in:TRKJ,TRM,TI',
            'kelas'         => 'required|in:A,B,C,D,E',
            'angkatan'      => 'required|digits:4',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        Mahasiswa::create([
            'nim'           => $request->nim,
            'nama'          => $request->nama,
            'prodi'         => $request->prodi,
            'kelas'         => $request->kelas,
            'angkatan'      => $request->angkatan,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return back()->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function update(Request $request, $lab, $id)
    {
        $request->validate([
            'nama'          => 'required',
            'prodi'         => 'required|in:TRKJ,TRM,TI',
            'kelas'         => 'required|in:A,B,C,D,E',
            'angkatan'      => 'required|digits:4',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        Mahasiswa::findOrFail($id)->update($request->all());

        return back()->with('success', 'Data mahasiswa diperbarui');
    }

    public function destroy($lab, $id)
    {
        Mahasiswa::findOrFail($id)->delete();
        return back()->with('success', 'Mahasiswa dihapus');
    }

    public function import(Request $request, $lab)
    {
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    try {
        Excel::import(new MahasiswaImport($lab), $request->file('file'));

        return back()->with('success', 'Import Excel berhasil');

    } catch (\Exception $e) {

        if ($e->getMessage() === 'FORMAT_EXCEL_SALAH') {
            return back()->with(
                'error',
                'Format Excel salah. Gunakan kolom: nim, nama mahasiswa, program studi, kelas, angkatan, jenis kelamin'
            );
        }

        return back()->with('error', 'Gagal import Excel');
    }
}


    public function bulkDelete(Request $request, $lab)
{
    if (!$request->has('ids')) {
        return back()->with('error', 'Tidak ada mahasiswa yang dipilih');
    }

    Mahasiswa::whereIn('id', $request->ids)->delete();

    return back()->with('success', 'Mahasiswa terpilih berhasil dihapus');
}

}
