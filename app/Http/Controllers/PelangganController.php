<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama'    => 'required|string|max:100',
            'telepon' => 'nullable|string|max:15',
            'alamat'  => 'nullable|string',
            'tipe'    => 'required|in:Umum,Loyal,VIP',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        Pelanggan::create([
            'nama'       => $request->nama,
            'telepon'    => $request->telepon,
            'alamat'     => $request->alamat,
            'tipe'       => $request->tipe,
            'poin'       => 0,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'Pelanggan berhasil ditambahkan!'
        ]);
    }

    public function show(Pelanggan $pelanggan)
    {
        return response()->json([
            'id'         => $pelanggan->id,
            'nama'       => $pelanggan->nama ?? '-',
            'telepon'    => $pelanggan->telepon ?? '-',
            'alamat'     => $pelanggan->alamat ?? '-',
            'tipe'       => $pelanggan->tipe ?? '-',
            'poin'       => $pelanggan->poin ?? '-',
            'created_at' => $pelanggan->created_at ? $pelanggan->created_at->format('Y-m-d H:i:s'): '-' ,
            'creator'    => $pelanggan->creator ? $pelanggan->creator->username : '-', 
            'updated_at' => $pelanggan->updated_at ? $pelanggan->updated_at->format('Y-m-d H:i:s'): '-',
            'updater'    => $pelanggan->updater ? $pelanggan->updater->username : '-', 
        ]);
    }

    public function edit(Pelanggan $pelanggan)
    {
        return response()->json($pelanggan);
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validator = Validator::make($request->all(), [
            'nama'    => 'required|string|max:100',
            'telepon' => 'nullable|string|max:15',
            'alamat'  => 'nullable|string',
            'tipe'    => 'required|in:Umum,Loyal,VIP',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        $pelanggan->update([
            'nama'       => $request->nama,
            'telepon'    => $request->telepon,
            'alamat'     => $request->alamat,
            'tipe'       => $request->tipe,
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'Pelanggan berhasil diubah!'
        ]);
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return response()->json([
            'success' => 'Pelanggan berhasil dihapus!'
        ]);    
    }
}