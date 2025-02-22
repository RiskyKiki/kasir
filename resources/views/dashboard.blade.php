@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Dashboard')

@section('content')
<div class="section-body">
    <!-- Ringkasan Penjualan -->
    <div class="row">
        <!-- Total Hari Ini -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h6 class="card-title">Total Hari Ini</h6>
                    <h3 class="card-text">Rp {{ number_format($totalHariIni, 2) }}</h3>
                </div>
            </div>
        </div>
        <!-- Total Minggu Ini -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h6 class="card-title">Total Minggu Ini</h6>
                    <h3 class="card-text">Rp {{ number_format($totalMingguIni, 2) }}</h3>
                </div>
            </div>
        </div>
        <!-- Total Bulan Ini -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h6 class="card-title">Total Bulan Ini</h6>
                    <h3 class="card-text">Rp {{ number_format($totalBulanIni, 2) }}</h3>
                </div>
            </div>
        </div>
        <!-- Transaksi Hari Ini -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h6 class="card-title">Transaksi Hari Ini</h6>
                    <h3 class="card-text">{{ $jumlahTransaksiHariIni }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan Mingguan -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h4>Grafik Penjualan Mingguan</h4>
        </div>
        <div class="card-body">
            <canvas id="chartPenjualan" height="150"></canvas>
        </div>
    </div>

    <!-- Grafik Jumlah Transaksi Mingguan -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h4>Grafik Jumlah Transaksi Mingguan</h4>
        </div>
        <div class="card-body">
            <canvas id="chartJumlahTransaksi" height="150"></canvas>
        </div>
    </div>

    <!-- Daftar Produk Hampir Habis -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h4>Daftar Produk Hampir Habis</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th>Minimal Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produkHampirHabis as $produk)
                        <tr>
                            <td>{{ $produk->kode }}</td>
                            <td>{{ $produk->nama }}</td>
                            <td>{{ $produk->stok }}</td>
                            <td>{{ $produk->min_stok }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h4>Transaksi Terbaru</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Kasir</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Tanggal Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksiTerbaru as $transaksi)
                        <tr>
                            <td>{{ $transaksi->id }}</td>
                            <td>{{ $transaksi->user->username ?? '-' }}</td>
                            <td>{{ $transaksi->pelanggan->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($transaksi->total, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Statistik Pelanggan -->
    <div class="row">
        <!-- Pelanggan Perunggu -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h6 class="card-title">Pelanggan Perunggu</h6>
                    <h3 class="card-text">{{ $pelangganPerunggu }}</h3>
                </div>
            </div>
        </div>
        <!-- Pelanggan Perak -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h6 class="card-title">Pelanggan Perak</h6>
                    <h3 class="card-text">{{ $pelangganPerak }}</h3>
                </div>
            </div>
        </div>
        <!-- Pelanggan Emas -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h6 class="card-title">Pelanggan Emas</h6>
                    <h3 class="card-text">{{ $pelangganEmas }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Poin Membership -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h4>Total Poin Membership</h4>
        </div>
        <div class="card-body">
            <h3>{{ $totalPoin }}</h3>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Sertakan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Grafik Penjualan Mingguan
    var ctx = document.getElementById('chartPenjualan').getContext('2d');
    var penjualanData = @json($penjualanMingguan);
    var labels = penjualanData.map(function(item) {
        return item.tanggal;
    });
    var data = penjualanData.map(function(item) {
        return item.total;
    });

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Penjualan',
                data: data,
                borderColor: '#4bc0c0',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Grafik Jumlah Transaksi Mingguan
    var ctx2 = document.getElementById('chartJumlahTransaksi').getContext('2d');
    var transaksiData = @json($jumlahTransaksiMingguan);
    var labels2 = transaksiData.map(function(item) {
        return item.tanggal;
    });
    var data2 = transaksiData.map(function(item) {
        return item.count;
    });

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labels2,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: data2,
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
