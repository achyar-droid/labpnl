@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-800">
            Peminjaman Barang — Lab {{ strtoupper($lab) }}
        </h1>
        <p class="text-sm text-gray-500">
            Pilih mahasiswa berdasarkan prodi, kelas, dan angkatan
        </p>
    </div>

    {{-- FILTER + SEARCH --}}
    <div class="bg-slate-50 rounded-lg p-4 mb-5">

        {{-- FILTER --}}
        <form method="GET" class="grid grid-cols-4 gap-3 mb-3">
            <select name="prodi" class="input-sm">
                <option value="">Prodi</option>
                @foreach(['TRKJ','TRM','TI'] as $p)
                    <option value="{{ $p }}" @selected($prodi==$p)>{{ $p }}</option>
                @endforeach
            </select>

            <select name="kelas" class="input-sm">
                <option value="">Kelas</option>
                @foreach(['A','B','C','D','E'] as $k)
                    <option value="{{ $k }}" @selected($kelas==$k)>{{ $k }}</option>
                @endforeach
            </select>

            <select name="angkatan" class="input-sm">
                <option value="">Angkatan</option>
                @for($y=date('Y');$y>=2018;$y--)
                    <option value="{{ $y }}" @selected($angkatan==$y)>{{ $y }}</option>
                @endfor
            </select>

            <button class="btn-dark-sm w-full">
                Filter
            </button>
        </form>

        {{-- SEARCH --}}
        <form method="GET" class="flex gap-3">
            <input type="hidden" name="prodi" value="{{ $prodi }}">
            <input type="hidden" name="kelas" value="{{ $kelas }}">
            <input type="hidden" name="angkatan" value="{{ $angkatan }}">

            <input
                name="q"
                value="{{ $q }}"
                placeholder="Cari NIM / Nama Mahasiswa"
                class="input-sm flex-1">

            <button class="btn-primary-sm">
                Cari
            </button>
        </form>
    </div>

    {{-- TABEL MAHASISWA --}}
    <div class="border rounded-lg overflow-hidden mt-6 max-h-[calc(100vh-310px)] overflow-y-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700 sticky top-0">
                <tr>
                    <th class="p-2 text-left">NIM</th>
                    <th class="p-2 text-left">Nama</th>
                    <th class="p-2 text-center">Prodi</th>
                    <th class="p-2 text-center">Kelas</th>
                    <th class="p-2 text-center">Angkatan</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
                </thead>

            <tbody>
            @forelse($mahasiswas as $m)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-2 text-left">{{ $m->nim }}</td>
                <td class="p-2 text-left">{{ $m->nama }}</td>
                <td class="p-2 text-center">{{ $m->prodi }}</td>
                <td class="p-2 text-center">{{ $m->kelas }}</td>
                <td class="p-2 text-center">{{ $m->angkatan }}</td>
                <td class="p-2 text-center">
                    <button
                        type="button"
                        onclick='openPinjamModal(@json($m))'
                        class="btn-primary-sm">
                        Pilih
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center p-4 text-gray-500">
                    Filter untuk menampilkan data
                </td>
            </tr>
            @endforelse
            </tbody>

        </table>
    </div>

</div>

{{-- ================= MODAL PINJAM ================= --}}
<dialog id="modalPinjam" class="rounded-xl p-6 w-[520px]">
    <h2 class="font-semibold text-lg mb-3">Peminjaman Barang</h2>

    <p class="text-sm text-gray-600 mb-4">
        Pinjam untuk:
        <span id="pinjamNama" class="font-semibold text-blue-600"></span>
        (<span id="pinjamNim"></span>)
    </p>

    <form method="POST" action="/dashboard/{{ $lab }}/peminjaman">
        @csrf
        <input type="hidden" name="mahasiswa_id" id="pinjamMahasiswaId">

        <div class="grid grid-cols-2 gap-3 mb-3">
            <select name="barang_id" class="input-sm" required>
                <option value="">Pilih Barang</option>
                @foreach($barangs as $b)
                    <option value="{{ $b->id }}">
                        {{ $b->nama_barang }} (stok {{ $b->jumlah }})
                    </option>
                @endforeach
            </select>

            <input
                type="number"
                name="jumlah"
                list="list-jumlah"
                min="1"
                placeholder="Jumlah"
                class="input-sm"
                required
                >
            <datalist id="list-jumlah">
                @for($i=1;$i<=10;$i++)
                    <option value="{{ $i }}">
                @endfor
            </datalist>

        </div>

        <input type="date" name="tanggal_pinjam" class="input-sm w-full mb-4" required>

        <div class="flex justify-end gap-2">
            <button type="button"
                onclick="modalPinjam.close()"
                class="btn-outline-sm">
                Batal
            </button>

            <button class="btn-success-sm">
                Pinjam
            </button>
        </div>
    </form>
</dialog>

{{-- SCRIPT --}}
<script>
function openPinjamModal(m){
    document.getElementById('pinjamMahasiswaId').value = m.id;
    document.getElementById('pinjamNama').innerText = m.nama;
    document.getElementById('pinjamNim').innerText = m.nim;

    modalPinjam.showModal();
}
</script>

{{-- STYLE --}}
<style>
.input-sm{
    border:1px solid #cbd5e1;
    border-radius:8px;
    padding:7px 10px;
    font-size:13px;
}
.input-sm:focus{ outline:none; border-color:#2563eb; }

.btn-primary-sm{
    background:#2563eb;color:white;padding:7px 18px;border-radius:8px;font-size:13px;
}
.btn-primary-sm:hover{ background:#1d4ed8; }

.btn-dark-sm{
    background:#1f2937;color:white;padding:7px 18px;border-radius:8px;font-size:13px;
}

.btn-success-sm{
    background:#16a34a;color:white;padding:7px 18px;border-radius:8px;font-size:13px;
}
.btn-success-sm:hover{ background:#15803d; }

.btn-outline-sm{
    border:1px solid #cbd5e1;padding:7px 18px;border-radius:8px;font-size:13px;
}
</style>
@endsection
