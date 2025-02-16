@extends('layouts.main')

@section('content')
<div class="container col-8 p-2 rounded m-0">
    <h2>Detail Produk</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $produk->id }}</td>
        </tr>
        <tr>
            <th>Kode Produk</th>
            <td>{{ $produk->kode_produk }}</td>
        </tr>
        <tr>
            <th>Nama Produk</th>
            <td>{{ $produk->nama_produk }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $produk->kategori->nama_kategori }}</td>
        </tr>
        <tr>
            <th>Harga Beli</th>
            <td>{{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Harga Jual</th>
            <td>{{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Stock</th>
            <td>{{ $produk->stock }}</td>
        </tr>
        <tr>
            <th>Minimal Stock</th>
            <td>{{ $produk->minimal_stock }}</td>
        </tr>
        <tr>
            <th>Tanggal Pembelian</th>
            <td>{{ $produk->tanggal_pembelian }}</td>
        </tr>
        <tr>
            <th>Dibuat Oleh</th>
            <td>{{ $produk->created_by ?? '-' }}</td>
        </tr>
        <tr>
            <th>Diupdate Oleh</th>
            <td>{{ $produk->updated_by ?? '-' }}</td>
        </tr>
    </table>
    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
