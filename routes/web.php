<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PeranController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\ReturPenjualanController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\PenyesuaianController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::view('/forgot-password', 'auth.forgot-password')->name('forgot-password');
Route::view('/reset-password', 'auth.reset-password')->name('reset-password');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('master')->name('master.')->group(function () {
        Route::get('pemasok/export/excel', [PemasokController::class, 'exportExcel'])->name('pemasok.export.excel');
        Route::get('pemasok/export/csv', [PemasokController::class, 'exportCsv'])->name('pemasok.export.csv');
        Route::get('pemasok/export/pdf', [PemasokController::class, 'exportPdf'])->name('pemasok.export.pdf');
        Route::resource('pemasok', PemasokController::class)->except(['show']);
        Route::get('kategori/export/excel', [KategoriController::class, 'exportExcel'])->name('kategori.export.excel');
        Route::get('kategori/export/csv', [KategoriController::class, 'exportCsv'])->name('kategori.export.csv');
        Route::get('kategori/export/pdf', [KategoriController::class, 'exportPdf'])->name('kategori.export.pdf');
        Route::resource('kategori', KategoriController::class)->except(['show']);
        Route::get('satuan/export/excel', [SatuanController::class, 'exportExcel'])->name('satuan.export.excel');
        Route::get('satuan/export/csv', [SatuanController::class, 'exportCsv'])->name('satuan.export.csv');
        Route::get('satuan/export/pdf', [SatuanController::class, 'exportPdf'])->name('satuan.export.pdf');
        Route::resource('satuan', SatuanController::class)->except(['show']);
        Route::get('produk/export/excel', [ProdukController::class, 'exportExcel'])->name('produk.export.excel');
        Route::get('produk/export/csv', [ProdukController::class, 'exportCsv'])->name('produk.export.csv');
        Route::get('produk/export/pdf', [ProdukController::class, 'exportPdf'])->name('produk.export.pdf');
        Route::resource('produk', ProdukController::class)->except(['show']);
    });

    Route::prefix('persediaan')->name('persediaan.')->group(function () {
        Route::resource('stok', StokController::class)->only(['index', 'show']);
        Route::resource('penyesuaian', PenyesuaianController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::resource('opname', OpnameController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    });

    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::resource('penjualan', PenjualanController::class)->except(['edit', 'update']);
        Route::resource('pembelian', PembelianController::class)->except(['edit', 'update']);
        Route::get('retur', [ReturController::class, 'index'])->name('retur.index');
        Route::get('retur/pembelian/create', [ReturPembelianController::class, 'create'])->name('retur.pembelian.create');
        Route::post('retur/pembelian', [ReturPembelianController::class, 'store'])->name('retur.pembelian.store');
        Route::get('retur/pembelian/{returPembelian}', [ReturPembelianController::class, 'show'])->name('retur.pembelian.show');
        Route::delete('retur/pembelian/{returPembelian}', [ReturPembelianController::class, 'destroy'])->name('retur.pembelian.destroy');
        Route::get('retur/penjualan/create', [ReturPenjualanController::class, 'create'])->name('retur.penjualan.create');
        Route::post('retur/penjualan', [ReturPenjualanController::class, 'store'])->name('retur.penjualan.store');
        Route::get('retur/penjualan/{returPenjualan}', [ReturPenjualanController::class, 'show'])->name('retur.penjualan.show');
        Route::delete('retur/penjualan/{returPenjualan}', [ReturPenjualanController::class, 'destroy'])->name('retur.penjualan.destroy');
    });

    Route::get('/pelanggan/export/excel', [PelangganController::class, 'exportExcel'])->name('pelanggan.export.excel');
    Route::get('/pelanggan/export/csv', [PelangganController::class, 'exportCsv'])->name('pelanggan.export.csv');
    Route::get('/pelanggan/export/pdf', [PelangganController::class, 'exportPdf'])->name('pelanggan.export.pdf');
    Route::resource('/pelanggan', PelangganController::class)->except(['show']);
    Route::resource('/dokter', DokterController::class)->except(['show']);
    Route::resource('/karyawan', KaryawanController::class)->except(['show']);
    Route::resource('/resep', ResepController::class)->except(['edit', 'update']);
    Route::get('/resep/{resep}/penjualan', [ResepController::class, 'createPenjualan'])->name('resep.penjualan.create');
    Route::post('/resep/{resep}/penjualan', [ResepController::class, 'storePenjualan'])->name('resep.penjualan.store');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('penjualan/export/excel', [LaporanController::class, 'exportPenjualanExcel'])->name('penjualan.export.excel');
        Route::get('penjualan/export/csv', [LaporanController::class, 'exportPenjualanCsv'])->name('penjualan.export.csv');
        Route::get('penjualan/export/pdf', [LaporanController::class, 'exportPenjualanPdf'])->name('penjualan.export.pdf');
        Route::get('pembelian', [LaporanController::class, 'pembelian'])->name('pembelian');
        Route::get('pembelian/export/excel', [LaporanController::class, 'exportPembelianExcel'])->name('pembelian.export.excel');
        Route::get('pembelian/export/csv', [LaporanController::class, 'exportPembelianCsv'])->name('pembelian.export.csv');
        Route::get('pembelian/export/pdf', [LaporanController::class, 'exportPembelianPdf'])->name('pembelian.export.pdf');
        Route::get('persediaan', [LaporanController::class, 'persediaan'])->name('persediaan');
        Route::get('persediaan/export/excel', [LaporanController::class, 'exportPersediaanExcel'])->name('persediaan.export.excel');
        Route::get('persediaan/export/csv', [LaporanController::class, 'exportPersediaanCsv'])->name('persediaan.export.csv');
        Route::get('persediaan/export/pdf', [LaporanController::class, 'exportPersediaanPdf'])->name('persediaan.export.pdf');
        Route::get('keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('keuangan/export/excel', [LaporanController::class, 'exportKeuanganExcel'])->name('keuangan.export.excel');
        Route::get('keuangan/export/csv', [LaporanController::class, 'exportKeuanganCsv'])->name('keuangan.export.csv');
        Route::get('keuangan/export/pdf', [LaporanController::class, 'exportKeuanganPdf'])->name('keuangan.export.pdf');
        Route::get('pelanggan', [LaporanController::class, 'pelanggan'])->name('pelanggan');
    });

    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        Route::resource('peran', PeranController::class)->except(['show']);
        Route::resource('hak-akses', HakAksesController::class)->except(['show']);
    });
    Route::resource('/pengguna', PenggunaController::class)->except(['show']);
});










