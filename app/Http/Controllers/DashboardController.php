<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan Penjualan
        $totalHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->sum('total');
        $totalMingguIni = Transaksi::whereBetween('tanggal_transaksi', [
            Carbon::now()->startOfWeek(), 
            Carbon::now()->endOfWeek()
        ])->sum('total');
        $totalBulanIni = Transaksi::whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('total');
        $jumlahTransaksiHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->count();

        // Grafik Penjualan Mingguan (7 hari terakhir)
        $penjualanMingguan = Transaksi::select(
                DB::raw("DATE(tanggal_transaksi) as tanggal"),
                DB::raw("SUM(total) as total")
            )
            ->whereBetween('tanggal_transaksi', [
                Carbon::now()->subDays(7), 
                Carbon::now()
            ])
            ->groupBy(DB::raw("DATE(tanggal_transaksi)"))
            ->orderBy('tanggal', 'ASC')
            ->get();

        // Daftar Produk Hampir Habis (stok <= minimal stok)
        $produkHampirHabis = Produk::whereColumn('stok', '<=', 'min_stok')->get();

        // Transaksi Terbaru (5 transaksi terakhir)
        $transaksiTerbaru = Transaksi::with(['user', 'pelanggan'])
            ->orderBy('tanggal_transaksi', 'DESC')
            ->take(5)
            ->get();

        // Statistik Pelanggan
        $pelangganPerunggu = Pelanggan::where('tipe', 'Perunggu')->count();
        $pelangganPerak    = Pelanggan::where('tipe', 'Perak')->count();
        $pelangganEmas     = Pelanggan::where('tipe', 'Emas')->count();
        $totalPoin       = Pelanggan::sum('poin');

        return view('dashboard', compact(
            'totalHariIni',
            'totalMingguIni',
            'totalBulanIni',
            'jumlahTransaksiHariIni',
            'penjualanMingguan',
            'produkHampirHabis',
            'transaksiTerbaru',
            'pelangganPerunggu',
            'pelangganPerak',
            'pelangganEmas',
            'totalPoin'
        ));
    }
}
