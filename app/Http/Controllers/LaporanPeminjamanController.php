<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPeminjamanController extends Controller
{
    public function index(Request $request, $lab)
    {
        $query = Peminjaman::with(['mahasiswa','barang'])
            ->where('lab', $lab);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', 'like', "%{$request->q}%")
                  ->orWhere('nama', 'like', "%{$request->q}%");
            });
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pinjam', date('m', strtotime($request->bulan)))
                  ->whereYear('tanggal_pinjam', date('Y', strtotime($request->bulan)));
        }

        if ($request->filled('from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->to);
        }

        $laporans = $query->latest()->get();

        return view('dashboard.laporan', compact('laporans','lab'));
    }

    // ======================
    // EXPORT LAPORAN
    // ======================
    public function export(Request $request, $lab)
    {
        $query = Peminjaman::with(['mahasiswa','barang'])
            ->where('lab', $lab);

        if ($request->periode == 'minggu') {
            $query->whereBetween('tanggal_pinjam', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        if ($request->periode == 'bulan') {
            $query->whereMonth('tanggal_pinjam', now()->month)
                  ->whereYear('tanggal_pinjam', now()->year);
        }

        if ($request->periode == 'custom') {
            $query->whereDate('tanggal_pinjam', '>=', $request->from)
                  ->whereDate('tanggal_pinjam', '<=', $request->to);
        }

        $laporans = $query->latest()->get();

        if ($request->type == 'print') {
            return view('dashboard.laporan-print', compact('laporans','lab'));
        }

        if ($request->type == 'pdf') {
            $pdf = pdf::loadView('dashboard.laporan-print', compact('laporans','lab'));
            return $pdf->download('laporan-peminjaman.pdf');
        }

        if ($request->type == 'word') {
            return response()->view('dashboard.laporan-print', compact('laporans','lab'))
                ->header("Content-Type", "application/vnd.ms-word")
                ->header("Content-Disposition", "attachment; filename=laporan-peminjaman.doc");
        }
    }
}
