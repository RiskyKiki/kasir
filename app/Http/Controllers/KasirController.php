<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Barryvdh\DomPDF\PDF;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();
        $produks = Produk::all();

        return view('kasir', compact('pelanggans', 'produks'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'pelanggan_id'      => 'required|exists:pelanggans,id',
            'items'             => 'required|array',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah'    => 'required|integer|min:1',
            'items.*.harga'     => 'required|numeric|min:0',      // Validasi harga yang dikirim
            'items.*.subtotal'  => 'required|numeric|min:0',      // Validasi subtotal per item
            'diskon'            => 'nullable|numeric',
            'pembayaran'        => 'required|numeric|min:0',
            'poin_digunakan'    => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Menggunakan data perhitungan yang sudah dikirim dari frontend
            $subtotal = 0;
            foreach ($request->items as $item) {
                // Asumsikan nilai subtotal yang dikirim adalah benar (Anda bisa tambahkan validasi tambahan jika perlu)
                $subtotal += $item['subtotal'];
            }

            // Ambil nilai diskon (jika ada) dan hitung total setelah diskon
            $diskon = $request->diskon ?? 0;
            $totalAfterDiscount = $subtotal - $diskon;

            // Hitung pajak 12%
            $pajak = $totalAfterDiscount * 0.12;
            $total = $totalAfterDiscount + $pajak;

            // Hitung poin yang didapat (misalnya 2% dari subtotal sebelum diskon)
            $poin_didapat = (int) ($subtotal * 0.02);
            $poin_digunakan = $request->poin_digunakan ? $request->poin_digunakan : 0;

            // Hitung kembalian
            $pembayaran = $request->pembayaran;
            $kembalian  = $pembayaran - $total;
            if ($kembalian < 0) {
                return response()->json(['error' => 'Nominal pembayaran kurang.'], 400);
            }

            // Simpan data header transaksi
            $transaksi = Transaksi::create([
                'pelanggan_id'      => $request->pelanggan_id,
                'user_id'           => Auth::user()->id,
                'tanggal_transaksi' => Carbon::now(),
                'subtotal'          => $subtotal,
                'diskon'            => $diskon,
                'pajak'             => $pajak,
                'total'             => $total,
                'pembayaran'        => $pembayaran,
                'kembalian'         => $kembalian,
                'poin_didapat'      => $poin_didapat,
                'poin_digunakan'    => $poin_digunakan,
            ]);

            // Simpan detail transaksi dan update stok produk
            foreach ($request->items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $item['produk_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga'        => $item['harga'],      // Menggunakan harga dari frontend
                    'subtotal'     => $item['subtotal'],   // Menggunakan subtotal dari frontend
                ]);

                // Update stok produk
                $produk = Produk::findOrFail($item['produk_id']);
                $produk->stok -= $item['jumlah'];
                $produk->save();
            }
            // Ambil data pelanggan berdasarkan ID
            $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);
            $pelanggan->poin = ($pelanggan->poin + $poin_didapat) - $poin_digunakan;
            $pelanggan->save();

            DB::commit();

            return response()->json(['redirect_url' => route('show-invoice', $transaksi->id)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showInvoice($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'pelanggan', 'user'])->findOrFail($id);
        // Pastikan Anda memiliki view "invoice.blade.php" di direktori resources/views/
        return view('invoice', compact('transaksi'));
    }

    public function printInvoice($id)
    {
        // Ambil data transaksi beserta relasinya
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'pelanggan', 'user'])->findOrFail($id);
        
        // Render view khusus PDF (invoice-pdf.blade.php)
        // $pdf = PDF::loadView('invoice-pdf', compact('transaksi'));
        $pdf = app('dompdf.wrapper')->loadView('invoice-pdf', compact('transaksi'));
        
        // Download file PDF dengan nama file sesuai ID transaksi
        return $pdf->download('invoice_' . $transaksi->id . '.pdf');
    }
}
