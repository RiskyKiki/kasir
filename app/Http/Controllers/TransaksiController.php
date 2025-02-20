<?php

namespace App\Http\Controllers;

use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dengan eager loading relasi yang diperlukan
        $query = Transaksi::with(['pelanggan', 'user']);

        // Terapkan filter hanya jika kedua input tanggal terisi
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        }

        $transaksis = $query->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function show($id)
    {
        // Ambil transaksi beserta relasinya
        $transaksi = Transaksi::with(['pelanggan', 'user', 'detailTransaksi.produk'])->findOrFail($id);

        // Kembalikan view untuk menampilkan detail transaksi
        return view('transaksi.show', compact('transaksi'));
    }

    public function exportExcel(Request $request)
    {
        // Ambil input tanggal; bila kosong, nilai null
        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');

        $fileName = 'transaksi_' . date('YmdHis') . '.xlsx';

        // Jika start_date dan end_date kosong, maka TransaksiExport akan mengembalikan semua data
        return Excel::download(new TransaksiExport($start_date, $end_date), $fileName);
    }
}
