<?php

namespace App\Providers;

use App\Models\BatchProduk;
use App\Models\Dokter;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Pemasok;
use App\Models\Pembelian;
use App\Models\Pengguna;
use App\Models\Penjualan;
use App\Models\PenyesuaianStok;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\ReturPembelian;
use App\Models\ReturPenjualan;
use App\Models\Satuan;
use App\Models\StokOpname;
use App\Observers\AuditTrailObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $observer = AuditTrailObserver::class;

        Produk::observe($observer);
        Kategori::observe($observer);
        Satuan::observe($observer);
        Pemasok::observe($observer);
        Pelanggan::observe($observer);
        Dokter::observe($observer);
        Pengguna::observe($observer);
        BatchProduk::observe($observer);

        Penjualan::observe($observer);
        Pembelian::observe($observer);
        ReturPenjualan::observe($observer);
        ReturPembelian::observe($observer);
        Resep::observe($observer);

        PenyesuaianStok::observe($observer);
        StokOpname::observe($observer);
    }
}