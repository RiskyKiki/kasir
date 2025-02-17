<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Katproduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        $kategoris = Katproduk::all();
        return view('produk.index', compact('produks','kategoris'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:produks,kode',
            'nama' => 'required',
            'kategori_id' => 'required|exists:katproduks,id',
            'hpp' => 'required|numeric|min:0',
            'harga1' => 'required|numeric|min:0',
            'harga2' => 'required|numeric|min:0',   
            'harga3' => 'required|numeric|min:0',   
            'stok' => 'required|integer|min:0',
            'min_stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        Produk::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'hpp' => $request->hpp,
            'harga1' => $request->harga1,
            'harga2' => $request->harga2,
            'harga3' => $request->harga3,
            'stok' => $request->stok,
            'min_stok' => $request->min_stok,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'Produk berhasil ditambahkan!'
        ]);
    }

    public function show($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json([
            'kode' => $produk->kode,
            'nama' => $produk->nama,
            'kategori' => $produk->kategori ? $produk->kategori->nama : '-',
            'tanggal_kadaluarsa' => $produk->tanggal_kadaluarsa,
            'tanggal_pembelian' => $produk->tanggal_pembelian,
            'hpp' => $produk->hpp,
            'harga1' => $produk->harga1,
            'harga2' => $produk->harga2,
            'harga3' => $produk->harga3,
            'stok' => $produk->stok,
            'min_stok' => $produk->min_stok,
            'created_at' => $produk->created_at ? $produk->created_at->format('Y-m-d H:i:s') : '-',
            'creator'    => $produk->creator ? $produk->creator->username : '-',
            'updated_at' => $produk->updated_at ? $produk->updated_at->format('Y-m-d H:i:s') : '-',
            'updater'    => $produk->updater ? $produk->updater->username : '-',
        ]);
    }

    public function edit($id)
    {
        $produk = Produk::find($id);
        $kategoris = Katproduk::all();
        
        if (!$produk) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }
        
        return response()->json($produk);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:produks,kode,' . $id,
            'nama' => 'required',
            'kategori_id' => 'required|exists:katproduks,id',
            'hpp' => 'required|numeric|min:0',
            'harga1' => 'required|numeric|min:0',
            'harga2' => 'required|numeric|min:0',   
            'harga3' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'min_stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        $produk->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'hpp' => $request->hpp,
            'harga1' => $request->harga1,
            'harga2' => $request->harga2,
            'harga3' => $request->harga3,
            'stok' => $request->stok,
            'min_stok' => $request->min_stok,
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'Produk berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            $produk->delete();
            return response()->json([
                'success' => 'Produk berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus produk!',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
