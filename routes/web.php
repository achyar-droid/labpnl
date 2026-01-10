<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\LaporanPeminjamanController;

// ==========================
// HALAMAN AWAL → AUTH
// ==========================
Route::get('/', function () {
    return redirect()->route('auth');
});

// halaman auth (login + register slide)
Route::get('/auth', function () {
    return view('auth.auth');
})->name('auth');

// ==========================
// DASHBOARD & MAHASISWA
// ==========================
Route::middleware(['auth'])->group(function () {

    // dashboard per lab
    Route::get('/dashboard/{lab}', function ($lab) {
        return view('dashboard.lab', compact('lab'));
    })->whereIn('lab', ['iot', 'jaringan', 'cloud']);

    // ======================
    // MAHASISWA (CRUD)
    // ======================
    Route::get(
        '/dashboard/{lab}/mahasiswa',
        [MahasiswaController::class, 'index']
    );

    Route::post(
        '/dashboard/{lab}/mahasiswa',
        [MahasiswaController::class, 'store']
    );

    Route::put(
        '/dashboard/{lab}/mahasiswa/{id}',
        [MahasiswaController::class, 'update']
    );

    Route::delete(
        '/dashboard/{lab}/mahasiswa/{id}',
        [MahasiswaController::class, 'destroy']
    );

    Route::post(
        '/dashboard/{lab}/mahasiswa/import',
        [MahasiswaController::class, 'import']
    );

    // CRUD BARANG
    Route::get('/dashboard/{lab}/barang', [BarangController::class, 'index']);
    Route::post('/dashboard/{lab}/barang', [BarangController::class, 'store']);
    Route::put('/dashboard/{lab}/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/dashboard/{lab}/barang/{id}', [BarangController::class, 'destroy']);

    // PEMINJAMAN
    Route::get('/dashboard/{lab}/peminjaman', [PeminjamanController::class, 'index']);
    Route::post('/dashboard/{lab}/peminjaman', [PeminjamanController::class, 'store']);

    // PENGEMBALIAN
    Route::get('/dashboard/{lab}/pengembalian', [PengembalianController::class, 'index']);
    Route::put('/dashboard/{lab}/pengembalian/{id}', [PengembalianController::class, 'kembalikan']);

    // HAPUS CEK MAHASISWA
    Route::post(
    '/dashboard/{lab}/mahasiswa/bulk-delete',
    [MahasiswaController::class, 'bulkDelete']);

    // LAPORAN PEMINJAMAN
    Route::get('/dashboard/{lab}/laporan-peminjaman',
    [LaporanPeminjamanController::class, 'index'])->middleware('auth');

    // LAPORAN PEMINJAMAN
    Route::get('/dashboard/{lab}/laporan-peminjaman',
    [LaporanPeminjamanController::class, 'index'])->middleware('auth');

    Route::get('/dashboard/{lab}/laporan-peminjaman/export',
    [LaporanPeminjamanController::class, 'export'])->middleware('auth');



});

// auth bawaan breeze
require __DIR__ . '/auth.php';
