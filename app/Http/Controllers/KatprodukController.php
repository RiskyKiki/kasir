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

    public function show($id)
    {
        $katproduk = Katproduk::find($id);

        if (!$katproduk) {
            return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
        }
        return response()->json([
            'id'         => $katproduk->id,
            'kode'       => $katproduk->kode ?? '-',
            'nama'       => $katproduk->nama ?? '-',
            'created_at' => $katproduk->created_at ? $katproduk->created_at->format('Y-m-d H:i:s') : '-',
            'creator'    => $katproduk->creator ? $katproduk->creator->username : '-',
            'updated_at' => $katproduk->updated_at ? $katproduk->updated_at->format('Y-m-d H:i:s') : '-',
            'updater'    => $katproduk->updater ? $katproduk->updater->username : '-',
        ]);
    }

    public function edit($id)
    {
        $katproduk = Katproduk::find($id);
        
        if (!$katproduk) {
            return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
        }
        
        return response()->json($katproduk);
    }

    public function update(Request $request, $id)
    {
        $katproduk = Katproduk::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:katproduks,kode,' . $id,
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        $katproduk->update([
            'kode'       => $request->kode,
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
}
