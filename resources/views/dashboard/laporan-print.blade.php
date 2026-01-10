<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body{ font-family: Arial, sans-serif; font-size:12px; }
        table{ width:100%; border-collapse:collapse; }
        th,td{ border:1px solid #000; padding:6px; text-align:left; }
        th{ background:#eee; }
    </style>
</head>
<body onload="window.print()">

<h2>Laporan Peminjaman — Lab {{ strtoupper($lab) }}</h2>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Tgl Pinjam</th>
            <th>Tgl Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporans as $l)
        <tr>
            <td>{{ $l->mahasiswa->nama }}</td>
            <td>{{ $l->barang->nama_barang }}</td>
            <td>{{ $l->jumlah }}</td>
            <td>{{ $l->tanggal_pinjam }}</td>
            <td>{{ $l->status=='dikembalikan' ? $l->tanggal_kembali : '-' }}</td>
            <td>{{ ucfirst($l->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
