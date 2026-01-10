<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\LaporanPeminjaman;

class PeminjamanController extends Controller
{
    public function index(Request $request, $lab)
    {
        $mahasiswas = collect();
        $selectedMahasiswa = null;

        // FILTER
        $prodi    = $request->prodi;
        $kelas    = $request->kelas;
        $angkatan = $request->angkatan;
        $q        = $request->q;

        /**
         * 🔥 MAHASISWA GLOBAL (TANPA LAB)
         */
        if ($prodi || $kelas || $angkatan || $q) {
            $mahasiswas = Mahasiswa::query()
                ->when($prodi, fn ($x) => $x->where('prodi', $prodi))
                ->when($kelas, fn ($x) => $x->where('kelas', $kelas))
                ->when($angkatan, fn ($x) => $x->where('angkatan', $angkatan))
                ->when($q, function ($x) use ($q) {
                    $x->where(function ($s) use ($q) {
                        $s->where('nim', 'like', "%$q%")
                          ->orWhere('nama', 'like', "%$q%");
                    });
                })
                ->orderBy('nama')
                ->get();
        }

        // MAHASISWA DIPILIH
        if ($request->mahasiswa_id) {
            $selectedMahasiswa = Mahasiswa::find($request->mahasiswa_id);
        }

        /**
         * 🔒 BARANG TETAP PER LAB
         */
        $barangs = Barang::where('lab', $lab)
            ->where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('dashboard.peminjaman', compact(
            'lab',
            'mahasiswas',
            'barangs',
            'selectedMahasiswa',
            'prodi',
            'kelas',
            'angkatan',
            'q'
        ));
    }

    /**
     * SIMPAN PEMINJAMAN
     */
    public function store(Request $request, $lab)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswas,id',
            'barang_id'     => 'required|exists:barangs,id',
            'jumlah'        => 'required|integer|min:1',
            'tanggal_pinjam'=> 'required|date',
        ]);

        $barang = Barang::where('id', $request->barang_id)
            ->where('lab', $lab)
            ->firstOrFail();

        if ($request->jumlah > $barang->jumlah) {
            return back()->withErrors([
                'jumlah' => 'Jumlah melebihi stok barang'
            ]);
        }

        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        // LAPORAN PINJAM
        LaporanPeminjaman::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'barang_id' => $request->barang_id,
            'lab' => $lab,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'dipinjam',
        ]);



        // SIMPAN PEMINJAMAN
        Peminjaman::create([
            'mahasiswa_id'  => $request->mahasiswa_id,
            'barang_id'     => $request->barang_id,
            'jumlah'        => $request->jumlah,
            'tanggal_pinjam'=> $request->tanggal_pinjam,
            'status'        => 'dipinjam',
            'lab'           => $lab,
        ]);

        // KURANGI STOK
        $barang->decrement('jumlah', $request->jumlah);

        return back()->with('success', 'Barang berhasil dipinjam');
    }
}
