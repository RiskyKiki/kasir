<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $start_date;
    protected $end_date;

    // Terima rentang tanggal dari konstruktor
    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
    }

    public function query()
{
    $query = Transaksi::query();

    // Filter hanya jika kedua tanggal diberikan
    if ($this->start_date && $this->end_date) {
        $query->whereBetween('tanggal_transaksi', [$this->start_date, $this->end_date]);
    }

    return $query;
}

    public function headings(): array
    {
        return [
            'ID',
            'Pelanggan ID',
            'User ID',
            'Tanggal Transaksi',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Total',
            'Pembayaran',
            'Kembalian',
            'Poin Didapat',
            'Poin Digunakan',
            'Created At',
            'Updated At',
        ];
    }
}
