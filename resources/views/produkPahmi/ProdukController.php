<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produk = Produk::with('kategori')->get();

        if($request->ajax()) {
            return DataTables::of($produk)->make(true);
        }
        return view('produk.index', compact('produk'));
    }

    public function getData()
    {
        $produk = Produk::with('kategori')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'kode_produk' => $item->kode_produk,
                'nama_produk' => $item->nama_produk,
                'kategori' => $item->kategori ? $item->kategori->nama_kategori : '-',
                'harga_jual' => $item->harga_jual,
                'stock' => $item->stock,
                'action' => view('produk._action', compact('item'))->render(),
            ];
        });

        return response()->json(['data' => $produk]);
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('produk.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produk',
            'nama_produk' => 'required',
            'kategoriid' => 'required',
            'harga_jual' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        Produk::create($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produk,kode_produk,' . $id,
            'nama_produk' => 'required',
            'kategoriid' => 'required',
            'harga_jual' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function show($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        return view('produk.show', compact('produk'));
    }
}
