@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-800">Data Barang</h1>
        <p class="text-sm text-gray-500">
            Selamat Datang, Pengelola Lab {{ strtoupper($lab) }}
        </p>
    </div>

    {{-- SEARCH + TAMBAH --}}
    <div class="flex justify-between items-center gap-3 mb-5">

        <form method="GET" class="flex-1 max-w-md">
            <div class="relative">
                <input
                    name="q"
                    value="{{ $q }}"
                    placeholder="Cari nama atau kategori barang..."
                    class="input-sm w-full pl-10">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                </svg>
            </div>
        </form>

        <button onclick="modalTambah.showModal()" class="btn-primary-sm">
            + Tambah Barang
        </button>
    </div>

    {{-- TABEL --}}
    <div class="table-wrapper border rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3 text-left">Nama Barang</th>
                    <th class="p-3 text-left">Kategori</th>
                    <th class="p-3 text-center">Jumlah</th>
                    <th class="p-3 text-center">Tanggal Tambah</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
                </thead>

            <tbody>
            @forelse($barangs as $b)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 text-left font-medium">{{ $b->nama_barang }}</td>
                <td class="p-3 text-left">{{ $b->kategori }}</td>

                <td class="p-3 text-center">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $b->jumlah <= 3 ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                        {{ $b->jumlah }}
                    </span>
                </td>

                <td class="p-3 text-center text-gray-500">
                    {{ $b->tanggal_tambah
                        ? \Carbon\Carbon::parse($b->tanggal_tambah)->format('d M Y')
                        : '-' }}
                </td>

                <td class="p-3 text-center space-x-2">
                    <button
                        onclick='openEditBarang(@json($b))'
                        class="text-blue-600 text-xs">
                        Edit
                    </button>

                    <form method="POST"
                        action="/dashboard/{{ $lab }}/barang/{{ $b->id }}"
                        class="inline"
                        onsubmit="return confirm('Hapus barang ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center p-6 text-gray-500">
                    Data barang kosong
                </td>
            </tr>
            @endforelse
            </tbody>

        </table>
    </div>

</div>

{{-- ================= MODAL TAMBAH ================= --}}
<dialog id="modalTambah" class="rounded-xl p-6 w-[420px]">
    <h2 class="font-semibold text-lg mb-4">Tambah Barang</h2>

    <form method="POST" action="/dashboard/{{ $lab }}/barang" class="space-y-3">
        @csrf

        <input name="nama_barang" placeholder="Nama Barang" class="input-sm w-full" required>

        <select name="kategori" class="input-sm w-full" required>
            <option value="">Pilih Kategori</option>
            @foreach([
                'Perangkat Utama','Perangkat Jaringan','Perangkat IoT & Embedded',
                'Perangkat Elektronik','Media & Penyimpanan','Kabel & Konektor',
                'Aksesoris & Peripheral','Peralatan Pendukung',
                'Lisensi & Software','Lain-lain'
            ] as $k)
                <option>{{ $k }}</option>
            @endforeach
        </select>

        <input type="number" name="jumlah" min="1" placeholder="Jumlah"
               class="input-sm w-full" required>

        <input type="date" name="tanggal_tambah"
               value="{{ date('Y-m-d') }}"
               class="input-sm w-full" required>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="modalTambah.close()" class="btn-outline-sm">
                Batal
            </button>
            <button class="btn-primary-sm">Simpan</button>
        </div>
    </form>
</dialog>

{{-- ================= MODAL EDIT ================= --}}
<dialog id="modalEdit" class="rounded-xl p-6 w-[420px]">
    <h2 class="font-semibold text-lg mb-4">Edit Barang</h2>

    <form method="POST" id="formEditBarang" class="space-y-3">
        @csrf
        @method('PUT')

        <input id="editNama" name="nama_barang" class="input-sm w-full" required>

        <select id="editKategori" name="kategori" class="input-sm w-full" required>
            @foreach([
                'Perangkat Utama','Perangkat Jaringan','Perangkat IoT & Embedded',
                'Perangkat Elektronik','Media & Penyimpanan','Kabel & Konektor',
                'Aksesoris & Peripheral','Peralatan Pendukung',
                'Lisensi & Software','Lain-lain'
            ] as $k)
                <option>{{ $k }}</option>
            @endforeach
        </select>

        <input id="editJumlah" type="number" name="jumlah"
               class="input-sm w-full" required>

        <input id="editTanggal" type="date" name="tanggal_tambah"
               class="input-sm w-full" required>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="modalEdit.close()" class="btn-outline-sm">
                Batal
            </button>
            <button class="btn-primary-sm">Update</button>
        </div>
    </form>
</dialog>

{{-- SCRIPT --}}
<script>
function openEditBarang(b){
    document.getElementById('formEditBarang').action =
        `/dashboard/{{ $lab }}/barang/${b.id}`;

    editNama.value = b.nama_barang;
    editKategori.value = b.kategori;
    editJumlah.value = b.jumlah;
    editTanggal.value = b.tanggal_tambah ?? '{{ date('Y-m-d') }}';

    modalEdit.showModal();
}
</script>

{{-- STYLE --}}
<style>
.input-sm{
    border:1px solid #cbd5e1;
    border-radius:8px;
    padding:8px 12px;
    font-size:13px;
}
.input-sm:focus{
    outline:none;
    border-color:#2563eb;
}
.btn-primary-sm{
    background:#2563eb;
    color:white;
    padding:8px 16px;
    border-radius:8px;
    font-size:13px;
}
.btn-primary-sm:hover{ background:#1d4ed8; }
.btn-outline-sm{
    border:1px solid #cbd5e1;
    padding:8px 16px;
    border-radius:8px;
    font-size:13px;
}
.btn-outline-sm:hover{ background:#f1f5f9; }
</style>
@endsection
