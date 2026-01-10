@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

<h1 class="text-xl font-bold mb-4">
    Laporan Peminjaman — Lab {{ strtoupper($lab) }}
</h1>

{{-- FILTER --}}
<form method="GET" class="flex flex-wrap gap-2 mb-4">
    <select name="status" class="input-sm">
        <option value="">Semua Status</option>
        <option value="dipinjam" @selected(request('status')=='dipinjam')>Dipinjam</option>
        <option value="dikembalikan" @selected(request('status')=='dikembalikan')>Dikembalikan</option>
    </select>

    <input type="month" name="bulan" value="{{ request('bulan') }}" class="input-sm">

    <input name="q" value="{{ request('q') }}" placeholder="Cari NIM / Nama" class="input-sm">

    <button class="btn-dark-sm">Filter</button>

    {{-- BUTTON CETAK --}}
    <button type="button" onclick="openModal()" class="btn-primary-sm">
        Cetak Laporan
    </button>
</form>

{{-- TABEL --}}
<div class="border rounded-lg overflow-auto max-h-[calc(100vh-400px)]">
<table class="w-full text-sm border rounded">
<thead class="bg-gray-100">
<tr>
    <th class="p-2 text-left">Nama</th>
    <th class="p-2 text-left">Barang</th>
    <th class="p-2 text-center">Jumlah</th>
    <th class="p-2 text-center">Tgl Pinjam</th>
    <th class="p-2 text-center">Tgl Kembali</th>
    <th class="p-2 text-center">Status</th>
</tr>
</thead>


<tbody>
@forelse($laporans as $l)
<tr class="border-t hover:bg-gray-50">
    <td class="p-2 text-left">{{ $l->mahasiswa->nama }}</td>
    <td class="p-2 text-left">{{ $l->barang->nama_barang }}</td>
    <td class="p-2 text-center">{{ $l->jumlah }}</td>
    <td class="p-2 text-center">{{ $l->tanggal_pinjam }}</td>

    <td class="p-2 text-center">
        {{ $l->status == 'dikembalikan'
            ? $l->tanggal_kembali
            : '-' }}
    </td>

    <td class="p-2 text-center">
        <span class="px-2 py-1 rounded text-xs
        {{ $l->status=='dikembalikan'
            ? 'bg-green-100 text-green-600'
            : 'bg-yellow-100 text-yellow-600' }}">
            {{ ucfirst($l->status) }}
        </span>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center p-4 text-gray-500">
        Belum ada laporan
    </td>
</tr>
@endforelse
</tbody>

</table>
</div>

</div>

{{-- MODAL CETAK --}}
<div id="modalCetak" class="modal hidden">
    <div class="modal-content">
        <h2 class="text-lg font-bold mb-4">Cetak Laporan</h2>

        <form method="GET" action="/dashboard/{{ $lab }}/laporan-peminjaman/export" target="_blank" class="space-y-3">

            <select name="periode" class="input-sm w-full" onchange="toggleTanggal(this.value)">
                <option value="minggu">Minggu Ini</option>
                <option value="bulan">Bulan Ini</option>
                <option value="custom">Custom Tanggal</option>
            </select>

            <div id="tanggalRange" class="hidden flex gap-2">
                <input type="date" name="from" class="input-sm w-full">
                <input type="date" name="to" class="input-sm w-full">
            </div>

            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal()" class="btn-dark-sm">Batal</button>

                <button name="type" value="print" class="btn-primary-sm">
                    Print
                </button>

                <button name="type" value="pdf" class="btn-success-sm">
                    PDF
                </button>

                <button name="type" value="word" class="btn-warning-sm">
                    Word
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.input-sm{
    border:1px solid #cbd5e1;
    border-radius:8px;
    padding:7px 10px;
    font-size:13px;
}
.input-sm:focus{ outline:none; border-color:#2563eb; }

.btn-dark-sm{
    background:#1f2937;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
}

.btn-primary-sm{
    background:#2563eb;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
}

.btn-success-sm{
    background:#16a34a;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
}

.btn-warning-sm{
    background:#f59e0b;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
}

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:50;
}
.modal-content{
    background:white;
    padding:20px;
    border-radius:12px;
    width:350px;
}
.hidden{ display:none; }
</style>

<script>
function openModal(){
    document.getElementById('modalCetak').classList.remove('hidden');
}
function closeModal(){
    document.getElementById('modalCetak').classList.add('hidden');
}
function toggleTanggal(val){
    document.getElementById('tanggalRange').classList.toggle('hidden', val !== 'custom');
}
</script>

@endsection
