<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;

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

        Route::resource('/barang', BarangController::class);
    });

    Route::middleware('role:admin,petugas')->group(function () {
        Route::resource('/transaksi', TransaksiController::class);
    });
});
