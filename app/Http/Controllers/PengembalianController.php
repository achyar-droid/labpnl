<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index($lab)
    {
        $peminjamans = Peminjaman::with(['mahasiswa','barang'])
            ->where('lab', $lab)
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        return view('dashboard.pengembalian', compact('peminjamans','lab'));
    }

    public function kembalikan($lab, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // UPDATE STATUS + TANGGAL KEMBALI OTOMATIS
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        // KEMBALIKAN STOK BARANG
        $peminjaman->barang->increment('jumlah', $peminjaman->jumlah);

        return back()->with('success', 'Barang berhasil dikembalikan');
    }
}
