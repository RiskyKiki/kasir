<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatprodukController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StokController;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::resource('/users', UserController::class);

        Route::resource('/pelanggan', PelangganController::class);

        Route::get('/produk/newkode', [ProdukController::class, 'getNewKode'])->name('produk.newkode');
        Route::resource('/produk', ProdukController::class);

        Route::get('/kategori/newkode', action: [KatprodukController::class, 'getNewKode'])->name('kategori.newkode');
        Route::resource('/kategori', KatprodukController::class);
    });

    Route::middleware('role:admin,petugas')->group(function () {

        Route::get('/transaksi/export', [TransaksiController::class, 'exportExcel'])->name('transaksi.export');
        Route::resource('/transaksi', TransaksiController::class);

        Route::get('/stok/export', [StokController::class, 'exportExcel'])->name('stok.export');
        Route::resource('/stok', StokController::class);

        Route::resource('/kasir', KasirController::class);

        Route::get('invoice/{id?}', [KasirController::class, 'showInvoice'])->name('show-invoice');
        Route::get('invoice/print/{id}', [KasirController::class, 'printInvoice'])->name('invoice.print');
    });
});
