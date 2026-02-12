<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('master')->group(function () {
        Route::resource('kategori', KategoriController::class);
        Route::resource('satuan', SatuanController::class);
        Route::resource('produk', ProdukController::class);
        Route::resource('pemasok', PemasokController::class);
    });

    Route::resource('pelanggan', PelangganController::class);
    Route::resource('dokter', DokterController::class);

    Route::prefix('transaksi')->group(function () {
        Route::resource('penjualan', PenjualanController::class);
        Route::resource('pembelian', PembelianController::class);
    });

    Route::prefix('resep')->group(function () {
        Route::resource('resep', ResepController::class);
    });

    Route::prefix('persediaan')->group(function () {
        Route::resource('stok', StokController::class)->only(['index', 'show']);
    });

    Route::prefix('laporan')->group(function () {
        Route::get('penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('produk', [LaporanController::class, 'produk'])->name('laporan.produk');
        Route::get('stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    });

    Route::resource('pengguna', PenggunaController::class);

    Route::get('/profile', [PenggunaController::class, 'profile'])->name('profile');
});
