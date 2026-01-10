@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-8">

    <h1 class="text-2xl font-bold mb-2">
        Dashboard Lab {{ strtoupper($lab) }}
    </h1>

    <p class="text-gray-500 mb-6">
        Selamat datang, silakan pilih menu di samping kiri
    </p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <a href="/dashboard/{{ $lab }}/barang"
           class="border rounded-lg p-4 hover:bg-blue-50 transition">
            <h3 class="font-semibold">📦 Data Barang</h3>
            <p class="text-sm text-gray-500">Kelola inventaris</p>
        </a>

        <a href="/dashboard/{{ $lab }}/mahasiswa"
           class="border rounded-lg p-4 hover:bg-blue-50 transition">
            <h3 class="font-semibold">👨‍🎓 Data Mahasiswa</h3>
            <p class="text-sm text-gray-500">Kelola peminjam</p>
        </a>

        <a href="/dashboard/{{ $lab }}/peminjaman"
           class="border rounded-lg p-4 hover:bg-blue-50 transition">
            <h3 class="font-semibold">📤 Peminjaman</h3>
            <p class="text-sm text-gray-500">Pinjam barang</p>
        </a>

        <a href="/dashboard/{{ $lab }}/pengembalian"
           class="border rounded-lg p-4 hover:bg-blue-50 transition">
            <h3 class="font-semibold">📥 Pengembalian</h3>
            <p class="text-sm text-gray-500">Kembalikan barang</p>
        </a>

    </div>
</div>
@endsection
