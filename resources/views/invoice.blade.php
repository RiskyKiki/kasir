<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice Transaksi</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body { padding: 20px; }
  </style>
</head>
<body>
  <div class="container">
      <h1 class="mb-4">Invoice Transaksi #{{ $transaksi->id }}</h1>
      <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div>
                Tanggal: {{ $transaksi->tanggal_transaksi }} <br>
                Kasir: {{ Auth::user()->username }} <br>
            </div>
            <div class="text-right">
                Pelanggan: {{ $transaksi->pelanggan->nama ?? 'Umum' }} <br>
                Nomor Telepon: {{ $transaksi->pelanggan->telepon }} <br>
                Alamat: {{ $transaksi->pelanggan->alamat }} <br>
            </div>
        </div>
          <div class="card-body">
              <table class="table table-bordered">
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
              <div class="mt-3 text-right">
                  <p>Subtotal: Rp {{ number_format($transaksi->subtotal, 2) }}</p>
                  <p>Diskon: Rp {{ number_format($transaksi->diskon, 2) }}</p>
                  <p>Pajak: Rp {{ number_format($transaksi->pajak, 2) }}</p>
                  <p>Total: Rp {{ number_format($transaksi->total, 2) }}</p>
                  <p>Poin Didapat: {{ $transaksi->poin_didapat }}</p>
                  <p>Poin Digunakan: {{ $transaksi->poin_digunakan ?? '-' }}</p>
              </div>
          </div>
      </div>
      <div class="mt-4 text-right">
          <a href="{{ route('kasir.index') }}" class="btn btn-primary">Kembali ke Menu Kasir</a>
          <a href="{{ route('invoice.print', $transaksi->id) }}" class="btn btn-info">Cetak PDF</a>
        </div>
    </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
