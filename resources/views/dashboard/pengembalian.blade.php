@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

<h1 class="text-2xl font-bold mb-4">
    Pengembalian Barang — Lab {{ strtoupper($lab) }}
</h1>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-3">
    {{ session('success') }}
</div>
@endif

<table class="w-full border text-sm">
<thead class="bg-gray-100">
<tr>
    <th class="p-2 text-left">Mahasiswa</th>
    <th class="p-2 text-left">Barang</th>
    <th class="p-2 text-center">Jumlah</th>
    <th class="p-2 text-center">Tgl Pinjam</th>
    <th class="p-2 text-center">Aksi</th>
</tr>
</thead>


<tbody>
@forelse($peminjamans as $p)
<tr class="border-b hover:bg-gray-50">
    <td class="p-2 text-left">
        {{ $p->mahasiswa->nama }} <br>
        <small class="text-gray-500">{{ $p->mahasiswa->nim }}</small>
    </td>
    <td class="p-2 text-left">{{ $p->barang->nama_barang }}</td>
    <td class="p-2 text-center">{{ $p->jumlah }}</td>
    <td class="p-2 text-center">{{ $p->tanggal_pinjam }}</td>
    <td class="p-2 text-center">
        <form method="POST"
            action="/dashboard/{{ $lab }}/pengembalian/{{ $p->id }}"
            onsubmit="return confirm('Yakin barang dikembalikan?')">
            @csrf
            @method('PUT')
            <button class="bg-green-600 text-white px-3 py-1 rounded text-xs">
                Kembalikan
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center p-4 text-gray-500">
        Tidak ada barang yang sedang dipinjam
    </td>
</tr>
@endforelse
</tbody>

</table>

</div>
@endsection
