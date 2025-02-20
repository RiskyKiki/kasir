<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Exports\StokExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StokController extends Controller
{ 
     public function index()
    {
        // Ambil semua data produk beserta kategori
        $produks = Produk::with('kategori')->orderBy('nama')->get();
        return view('stok.index', compact('produks'));
    }

    public function exportExcel()
    {
        return Excel::download(new StokExport, 'laporan_stok.xlsx');
    }
}
