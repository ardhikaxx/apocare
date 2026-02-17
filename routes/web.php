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
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\BackupController;

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
    Route::get('/smart-search', [SearchController::class, '__invoke'])
        ->middleware('role:admin,apoteker,kasir,gudang')
        ->name('app.search');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,apoteker,kasir,gudang')
        ->name('dashboard');

    Route::get('/profil', [ProfilController::class, 'edit'])
        ->middleware('role:admin,apoteker,kasir,gudang')
        ->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])
        ->middleware('role:admin,apoteker,kasir,gudang')
        ->name('profil.update');

    Route::prefix('master')->name('master.')->middleware('role:admin,apoteker,gudang')->group(function () {
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
        Route::patch('produk/{produk}/favorit', [ProdukController::class, 'toggleFavorit'])->name('produk.favorit');
        Route::resource('produk', ProdukController::class)->except(['show']);
    });

    Route::prefix('persediaan')->name('persediaan.')->middleware('role:admin,apoteker,gudang')->group(function () {
        Route::resource('stok', StokController::class)->only(['index', 'show']);
        Route::resource('penyesuaian', PenyesuaianController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::resource('opname', OpnameController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    });

    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::post('penjualan/sync', [PenjualanController::class, 'syncOffline'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('penjualan.sync');
        Route::get('penjualan/{penjualan}/print', [PenjualanController::class, 'print'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('penjualan.print');
        Route::resource('penjualan', PenjualanController::class)
            ->middleware('role:admin,apoteker,kasir')
            ->except(['edit', 'update']);

        Route::resource('pembelian', PembelianController::class)
            ->middleware('role:admin,gudang')
            ->except(['edit', 'update']);

        Route::get('retur', [ReturController::class, 'index'])
            ->middleware('role:admin,apoteker,kasir,gudang')
            ->name('retur.index');

        Route::get('retur/pembelian/create', [ReturPembelianController::class, 'create'])
            ->middleware('role:admin,gudang')
            ->name('retur.pembelian.create');
        Route::post('retur/pembelian', [ReturPembelianController::class, 'store'])
            ->middleware('role:admin,gudang')
            ->name('retur.pembelian.store');
        Route::get('retur/pembelian/{returPembelian}', [ReturPembelianController::class, 'show'])
            ->middleware('role:admin,gudang')
            ->name('retur.pembelian.show');
        Route::delete('retur/pembelian/{returPembelian}', [ReturPembelianController::class, 'destroy'])
            ->middleware('role:admin,gudang')
            ->name('retur.pembelian.destroy');

        Route::get('retur/penjualan/create', [ReturPenjualanController::class, 'create'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('retur.penjualan.create');
        Route::post('retur/penjualan', [ReturPenjualanController::class, 'store'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('retur.penjualan.store');
        Route::get('retur/penjualan/{returPenjualan}', [ReturPenjualanController::class, 'show'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('retur.penjualan.show');
        Route::delete('retur/penjualan/{returPenjualan}', [ReturPenjualanController::class, 'destroy'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('retur.penjualan.destroy');
    });

    Route::get('/pelanggan/export/excel', [PelangganController::class, 'exportExcel'])
        ->middleware('role:admin,apoteker,kasir')
        ->name('pelanggan.export.excel');
    Route::get('/pelanggan/export/csv', [PelangganController::class, 'exportCsv'])
        ->middleware('role:admin,apoteker,kasir')
        ->name('pelanggan.export.csv');
    Route::get('/pelanggan/export/pdf', [PelangganController::class, 'exportPdf'])
        ->middleware('role:admin,apoteker,kasir')
        ->name('pelanggan.export.pdf');
    Route::resource('/pelanggan', PelangganController::class)
        ->middleware('role:admin,apoteker,kasir')
        ->except(['show']);

    Route::resource('/dokter', DokterController::class)
        ->middleware('role:admin,apoteker')
        ->except(['show']);

    Route::resource('/karyawan', KaryawanController::class)
        ->middleware('role:admin')
        ->except(['show']);

    Route::resource('/resep', ResepController::class)
        ->middleware('role:admin,apoteker,kasir')
        ->except(['edit', 'update']);

    Route::patch('/resep/{resep}/tahap', [ResepController::class, 'updateTahap'])
        ->middleware('role:admin,apoteker,kasir')
        ->name('resep.tahap.update');
    Route::get('/resep/{resep}/penjualan', [ResepController::class, 'createPenjualan'])
        ->middleware('role:admin,apoteker,kasir')
        ->name('resep.penjualan.create');
    Route::post('/resep/{resep}/penjualan', [ResepController::class, 'storePenjualan'])
        ->middleware('role:admin,apoteker,kasir')
        ->name('resep.penjualan.store');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('penjualan', [LaporanController::class, 'penjualan'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('penjualan');
        Route::get('penjualan/export/excel', [LaporanController::class, 'exportPenjualanExcel'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('penjualan.export.excel');
        Route::get('penjualan/export/csv', [LaporanController::class, 'exportPenjualanCsv'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('penjualan.export.csv');
        Route::get('penjualan/export/pdf', [LaporanController::class, 'exportPenjualanPdf'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('penjualan.export.pdf');
        Route::get('pembelian', [LaporanController::class, 'pembelian'])
            ->middleware('role:admin,apoteker,gudang')
            ->name('pembelian');
        Route::get('pembelian/export/excel', [LaporanController::class, 'exportPembelianExcel'])
            ->middleware('role:admin,apoteker,gudang')
            ->name('pembelian.export.excel');
        Route::get('pembelian/export/csv', [LaporanController::class, 'exportPembelianCsv'])
            ->middleware('role:admin,apoteker,gudang')
            ->name('pembelian.export.csv');
        Route::get('pembelian/export/pdf', [LaporanController::class, 'exportPembelianPdf'])
            ->middleware('role:admin,apoteker,gudang')
            ->name('pembelian.export.pdf');
        Route::get('persediaan', [LaporanController::class, 'persediaan'])
            ->middleware('role:admin,apoteker,gudang')
            ->name('persediaan');
        Route::get('persediaan/export/excel', [LaporanController::class, 'exportPersediaanExcel'])
            ->middleware('role:admin,apoteker,gudang')
            ->name('persediaan.export.excel');
        Route::get('persediaan/export/csv', [LaporanController::class, 'exportPersediaanCsv'])
            ->middleware('role:admin,apoteker,gudang')
            ->name('persediaan.export.csv');
        Route::get('persediaan/export/pdf', [LaporanController::class, 'exportPersediaanPdf'])
            ->middleware('role:admin,apoteker,gudang')
            ->name('persediaan.export.pdf');
        Route::get('keuangan', [LaporanController::class, 'keuangan'])
            ->middleware('role:admin')
            ->name('keuangan');
        Route::get('keuangan/export/excel', [LaporanController::class, 'exportKeuanganExcel'])
            ->middleware('role:admin')
            ->name('keuangan.export.excel');
        Route::get('keuangan/export/csv', [LaporanController::class, 'exportKeuanganCsv'])
            ->middleware('role:admin')
            ->name('keuangan.export.csv');
        Route::get('keuangan/export/pdf', [LaporanController::class, 'exportKeuanganPdf'])
            ->middleware('role:admin')
            ->name('keuangan.export.pdf');
        Route::get('pelanggan', [LaporanController::class, 'pelanggan'])
            ->middleware('role:admin,apoteker,kasir')
            ->name('pelanggan');
    });

    Route::prefix('pengguna')->name('pengguna.')->middleware('role:admin')->group(function () {
        Route::resource('peran', PeranController::class)->except(['show']);
        Route::resource('hak-akses', HakAksesController::class)->except(['show']);
    });

    Route::resource('/pengguna', PenggunaController::class)
        ->middleware('role:admin')
        ->except(['show']);

    Route::get('/audit', [AuditTrailController::class, 'index'])
        ->middleware('role:admin')
        ->name('audit.index');

    Route::get('/backup', [BackupController::class, 'index'])
        ->middleware('role:admin')
        ->name('backup.index');
    Route::post('/backup', [BackupController::class, 'create'])
        ->middleware('role:admin')
        ->name('backup.create');
    Route::get('/backup/{filename}/download', [BackupController::class, 'download'])
        ->middleware('role:admin')
        ->name('backup.download');
    Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('backup.destroy');
});
