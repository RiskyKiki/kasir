<?php

namespace App\Http\Controllers;

use App\Models\Katproduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KatprodukController extends Controller
{
    public function index()
    {
        $katproduks = Katproduk::all();

        return view('katproduk.index', compact('katproduks'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:katproduks,kode',
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        Katproduk::create([
            'kode'       => $request->kode,
            'nama'       => $request->nama,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'Kategori berhasil ditambahkan!'
        ]);
    }

    public function show(Katproduk $kategori)
    {
        return response()->json([
            'kode'       => $kategori->kode ?? '-',
            'nama'       => $kategori->nama ?? '-',
            'created_at' => $kategori->created_at ? $kategori->created_at->format('Y-m-d H:i:s') : '-',
            'creator'    => $kategori->creator ? $kategori->creator->username : '-',
            'updated_at' => $kategori->updated_at ? $kategori->updated_at->format('Y-m-d H:i:s') : '-',
            'updater'    => $kategori->updater ? $kategori->updater->username : '-',
        ]);
    }


    public function edit(Katproduk $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, Katproduk $kategori)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        $kategori->update([
            'nama'       => $request->nama,
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'Kategori berhasil diubah!'
        ]);
    }

    public function destroy($id)
    {
        try {
            $katproduk = Katproduk::findOrFail($id);
            $katproduk->delete();
            return response()->json([
                'success' => 'Kategori berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus kategori!',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getNewKode()
    {
        $lastCategory = Katproduk::withTrashed()->latest()->first();
    
        if ($lastCategory) {
            $number = intval(substr($lastCategory->kode, 3)) + 1;
            $kodeTerbaru = 'CAT' . str_pad($number, 3, '0', STR_PAD_LEFT);
        } else {
            $kodeTerbaru = 'CAT001';
        }
    
        return response()->json(['kodeTerbaru' => $kodeTerbaru]);
    }
    
}
