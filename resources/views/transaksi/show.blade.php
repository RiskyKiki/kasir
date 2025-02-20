@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Transaksi #{{ $transaksi->id }}</h1>
    
    <!-- Informasi Transaksi -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Kolom 1 -->
                <div class="col-md-4">
                    <p><strong>Pelanggan:</strong> {{ $transaksi->pelanggan->nama ?? '-' }}</p>
                    <p><strong>User:</strong> {{ $transaksi->user->username ?? '-' }}</p>
                    <p><strong>Tanggal Transaksi:</strong> {{ $transaksi->tanggal_transaksi }}</p>
                </div>
                <!-- Kolom 2 -->
                <div class="col-md-4">
                    <p><strong>Subtotal:</strong> Rp{{ number_format($transaksi->subtotal, 2, ',', '.') }}</p>
                    <p><strong>Diskon:</strong> Rp{{ number_format($transaksi->diskon, 2, ',', '.') }}</p>
                    <p><strong>Pajak:</strong> Rp{{ number_format($transaksi->pajak, 2, ',', '.') }}</p>
                    <p><strong>Total:</strong> Rp{{ number_format($transaksi->total, 2, ',', '.') }}</p>
                </div>
                <!-- Kolom 3 -->
                <div class="col-md-4">
                    <p><strong>Pembayaran:</strong> Rp{{ number_format($transaksi->pembayaran, 2, ',', '.') }}</p>
                    <p><strong>Kembalian:</strong> Rp{{ number_format($transaksi->kembalian, 2, ',', '.') }}</p>
                    <p><strong>Poin Didapat:</strong> {{ $transaksi->poin_didapat }}</p>
                    <p><strong>Poin Digunakan:</strong> {{ $transaksi->poin_digunakan }}</p>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Detail Item Transaksi -->
    <div class="card">
        <div class="card-header">
            <h3>Produk Transaksi</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi->detailTransaksi as $detail)
                    <tr>
                        <td>{{ $detail->produk->nama ?? '-' }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp{{ number_format($detail->harga, 2, ',', '.') }}</td>
                        <td>Rp{{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada item transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary mt-3">Kembali ke Laporan Transaksi</a>
</div>
@endsection
