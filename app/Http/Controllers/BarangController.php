<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request, $lab)
{
    $q = $request->q;

    $barangs = Barang::where('lab', $lab)
        ->when($q, function ($query) use ($q) {
            $query->where('nama_barang', 'like', "%$q%")
                  ->orWhere('kategori', 'like', "%$q%");
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return view('dashboard.barang', compact('barangs', 'lab', 'q'));
}



    public function store(Request $request, $lab)
{
    $request->validate([
        'nama_barang' => 'required',
        'kategori'    => 'required',
        'jumlah'      => 'required|integer|min:1',
    ]);

    Barang::create([
        'nama_barang'   => $request->nama_barang,
        'kategori'      => $request->kategori,
        'jumlah'        => $request->jumlah,
        'tanggal_tambah'=> $request->tanggal_tambah,
        'lab'           => $lab,
    ]);

    return back()->with('success', 'Barang berhasil ditambahkan');
}


    public function update(Request $request, $lab, $id)
    {
        Barang::where('id', $id)->update([
            'nama_barang' => $request->nama_barang,
            'kategori'    => $request->kategori,
            'jumlah'      => $request->jumlah,
        ]);

        return back()->with('success', 'Barang berhasil diupdate');
    }

    public function destroy($lab, $id)
    {
        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang berhasil dihapus');
    }
}
