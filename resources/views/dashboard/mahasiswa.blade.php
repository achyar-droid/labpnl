@extends('layouts.app')

@if(session('success'))
<div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
    {{ session('error') }}
</div>
@endif

@section('content')
<div class="bg-white rounded-xl shadow p-6">

{{-- HEADER --}}
<div class="mb-5">
    <h1 class="text-xl font-bold text-gray-800">Data Mahasiswa</h1>
    <p class="text-sm text-gray-500">
        Selamat Datang, Pengelola Lab {{ strtoupper($lab) }}
    </p>
</div>

{{-- ================= TAMBAH MAHASISWA ================= --}}
<form method="POST" action="{{ url("/dashboard/$lab/mahasiswa") }}"
      class="grid grid-cols-7 gap-2 mb-4">
@csrf
<input name="nim" placeholder="NIM" class="input-sm" required>
<input name="nama" placeholder="Nama Mahasiswa" class="input-sm" required>

<select name="prodi" class="input-sm" required>
    <option value="">Prodi</option>
    <option>TRKJ</option>
    <option>TRM</option>
    <option>TI</option>
</select>

<select name="kelas" class="input-sm" required>
    <option value="">Kelas</option>
    @foreach(['A','B','C','D','E'] as $k)
        <option>{{ $k }}</option>
    @endforeach
</select>

<select name="angkatan" class="input-sm" required>
    <option value="">Angkatan</option>
    @for($y=2020;$y<=date('Y')+1;$y++)
        <option>{{ $y }}</option>
    @endfor
</select>

<select name="jenis_kelamin" class="input-sm" required>
    <option value="">JK</option>
    <option value="L">L</option>
    <option value="P">P</option>
</select>

<button class="btn-primary-sm">Tambah</button>
</form>

{{-- ================= IMPORT EXCEL ================= --}}
<form method="POST"
      action="{{ url("/dashboard/$lab/mahasiswa/import") }}"
      enctype="multipart/form-data"
      class="flex gap-2 mb-5">
@csrf
<input type="file" name="file" required>
<button class="btn-success-sm">Import Excel</button>
</form>

{{-- ================= FILTER & SEARCH ================= --}}
<form method="GET" class="grid grid-cols-6 gap-2 mb-4">
<select name="prodi" class="input-sm">
    <option value="">Semua Prodi</option>
    @foreach(['TRKJ','TRM','TI'] as $p)
        <option value="{{ $p }}" @selected($prodi==$p)>{{ $p }}</option>
    @endforeach
</select>

<select name="kelas" class="input-sm">
    <option value="">Semua Kelas</option>
    @foreach(['A','B','C','D','E'] as $k)
        <option value="{{ $k }}" @selected($kelas==$k)>{{ $k }}</option>
    @endforeach
</select>

<select name="angkatan" class="input-sm">
    <option value="">Semua Angkatan</option>
    @for($y=2020;$y<=date('Y')+1;$y++)
        <option value="{{ $y }}" @selected($angkatan==$y)>{{ $y }}</option>
    @endfor
</select>

<input name="q" value="{{ $q }}" placeholder="Cari NIM / Nama" class="input-sm">

<button class="btn-dark-sm">Filter / Cari</button>
<a href="{{ url("/dashboard/$lab/mahasiswa") }}" class="btn-outline-sm text-center">Reset</a>
</form>

{{-- ================= BULK DELETE ================= --}}
<form id="bulkForm" method="POST" action="{{ url("/dashboard/$lab/mahasiswa/bulk-delete") }}">
@csrf
<button class="btn-danger-sm mb-3">Hapus Terpilih</button>
</form>

<div class="border rounded-lg overflow-hidden mt-6 max-h-[calc(100vh-400px)] overflow-y-auto">
<table class="w-full text-sm">

<thead class="bg-gray-100">
<tr>
    <th class="p-2 text-center w-10">
        <input type="checkbox"
        onclick="document.querySelectorAll('.ck').forEach(c=>c.checked=this.checked)">
    </th>
    <th class="p-2 text-left">NIM</th>
    <th class="p-2 text-left">Nama</th>
    <th class="p-2 text-center">Prodi</th>
    <th class="p-2 text-center">Kelas</th>
    <th class="p-2 text-center">Angkatan</th>
    <th class="p-2 text-center">JK</th>
    <th class="p-2 text-center">Aksi</th>
</tr>
</thead>

<tbody>
@forelse($mahasiswas as $m)
<tr class="border-t hover:bg-gray-50">

<td class="p-2 text-center w-10">
    <input type="checkbox" name="ids[]" value="{{ $m->id }}" class="ck" form="bulkForm">
</td>

<td class="p-2 text-left">{{ $m->nim }}</td>
<td class="p-2 text-left">{{ $m->nama }}</td>
<td class="p-2 text-center">{{ $m->prodi }}</td>
<td class="p-2 text-center">{{ $m->kelas }}</td>
<td class="p-2 text-center">{{ $m->angkatan }}</td>
<td class="p-2 text-center">
    <span class="px-2 py-1 rounded-full text-xs
    {{ $m->jenis_kelamin=='L'?'bg-blue-100 text-blue-600':'bg-pink-100 text-pink-600' }}">
    {{ $m->jenis_kelamin }}
    </span>
</td>

<td class="p-2 text-center space-x-2">
    <button type="button"
        onclick='openEditMahasiswa(@json($m))'
        class="btn-primary-xs">Edit</button>

    <form method="POST"
          action="{{ url("/dashboard/$lab/mahasiswa/$m->id") }}"
          class="inline"
          onsubmit="return confirm('Hapus mahasiswa ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger-xs">Hapus</button>
    </form>
</td>

</tr>
@empty
<tr>
<td colspan="8" class="text-center p-4 text-gray-500">
    Filter untuk menampilkan data
</td>
</tr>
@endforelse
</tbody>

</table>
</div>

{{-- ================= MODAL EDIT ================= --}}
<dialog id="modalEditMahasiswa" class="rounded-xl p-6 w-[520px]">
<h2 class="font-semibold text-lg mb-4">Edit Mahasiswa</h2>

<form method="POST" id="formEditMahasiswa" class="grid grid-cols-2 gap-3">
@csrf
@method('PUT')

<input id="editNama" name="nama" class="input-sm col-span-2" required>

<select id="editProdi" name="prodi" class="input-sm" required>
<option>TRKJ</option>
<option>TRM</option>
<option>TI</option>
</select>

<select id="editKelas" name="kelas" class="input-sm" required>
@foreach(['A','B','C','D','E'] as $k)
<option>{{ $k }}</option>
@endforeach
</select>

<input id="editAngkatan" name="angkatan" class="input-sm" required>

<select id="editJK" name="jenis_kelamin" class="input-sm" required>
<option value="L">L</option>
<option value="P">P</option>
</select>

<div class="col-span-2 flex justify-end gap-2 pt-2">
<button type="button" onclick="modalEditMahasiswa.close()" class="btn-outline-sm">Batal</button>
<button class="btn-primary-sm">Update</button>
</div>
</form>
</dialog>

<script>
function openEditMahasiswa(m){
    const form = document.getElementById('formEditMahasiswa');
    form.action = `{{ url("/dashboard/$lab/mahasiswa") }}/${m.id}`;

    document.getElementById('editNama').value = m.nama;
    document.getElementById('editProdi').value = m.prodi;
    document.getElementById('editKelas').value = m.kelas;
    document.getElementById('editAngkatan').value = m.angkatan;
    document.getElementById('editJK').value = m.jenis_kelamin;

    document.getElementById('modalEditMahasiswa').showModal();
}
</script>

<style>
.input-sm{border:1px solid #cbd5e1;border-radius:8px;padding:8px 12px;font-size:13px}
.input-sm:focus{outline:none;border-color:#2563eb}
.btn-primary-sm{background:#2563eb;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px}
.btn-primary-xs{background:#2563eb;color:#fff;padding:4px 10px;border-radius:6px;font-size:12px}
.btn-success-sm{background:#16a34a;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px}
.btn-danger-sm{background:#dc2626;color:#fff;padding:6px 14px;border-radius:8px;font-size:13px}
.btn-danger-xs{background:#dc2626;color:#fff;padding:4px 10px;border-radius:6px;font-size:12px}
.btn-dark-sm{background:#111827;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px}
.btn-outline-sm{border:1px solid #cbd5e1;padding:8px 16px;border-radius:8px;font-size:13px}
</style>
@endsection
