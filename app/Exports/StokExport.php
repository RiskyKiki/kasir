<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokExport implements FromCollection, WithHeadings
{
    /**
     * Mengembalikan koleksi data produk untuk diexport ke Excel.
     */
    public function collection()
    {
        // Ambil semua data produk dengan relasi kategori
        $produks = Produk::with('kategori')->get();

        // Map data untuk mendapatkan format yang diinginkan
        return $produks->map(function($produk) {
            return [
                'Kode Barang'     => $produk->kode,
                'Nama Barang'     => $produk->nama,
                'Kategori Barang' => $produk->kategori->nama ?? '-',
                'Sisa Stok'       => $produk->stok,
                'Status'          => $this->getStatus($produk),
            ];
        });
    }

    /**
     * Menentukan judul kolom pada file Excel.
     */
    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Kategori Barang',
            'Sisa Stok',
            'Status',
        ];
    }

    private function getStatus($produk)
    {
        if ($produk->stok == 0) {
            return 'Habis';
        } elseif ($produk->stok <= $produk->min_stok) {
            return 'Menipis';
        }
        return 'Masih';
    }
}
