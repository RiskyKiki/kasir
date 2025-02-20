<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Transaksi #{{ $transaksi->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details table, .invoice-details th, .invoice-details td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Invoice Transaksi #{{ $transaksi->id }}</h2>
        <div class="invoice-header">
            <div>
                <p>Tanggal: {{ $transaksi->tanggal_transaksi }}</p>
                <p>Kasir: {{ $transaksi->user->username }}</p>
            </div>
            <div class="text-right">
                <p>Pelanggan: {{ $transaksi->pelanggan->nama ?? 'Umum' }}</p>
                <p>Nomor Telepon: {{ $transaksi->pelanggan->telepon }}</p>
                <p>Alamat: {{ $transaksi->pelanggan->alamat }}</p>
            </div>
        </div>
        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->detailTransaksi as $detail)
                    <tr>
                        <td>{{ $detail->produk->nama }}</td>
                        <td>Rp {{ number_format($detail->harga, 2) }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-right" style="margin-top: 20px;">
            <p>Subtotal: Rp {{ number_format($transaksi->subtotal, 2) }}</p>
            <p>Diskon: Rp {{ number_format($transaksi->diskon, 2) }}</p>
            <p>Pajak: Rp {{ number_format($transaksi->pajak, 2) }}</p>
            <p>Total: Rp {{ number_format($transaksi->total, 2) }}</p>
            <p>Poin Didapat: {{ $transaksi->poin_didapat }}</p>
            <p>Poin Digunakan: {{ $transaksi->poin_digunakan ?? '-' }}</p>
        </div>
    </div>
</body>
</html>
