<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';
    protected $fillable = [
        'kode', 'barcode', 'nama', 'nama_generik', 'kategori_id', 'satuan_id', 'produsen',
        'keterangan', 'jenis_produk', 'golongan_obat', 'perlu_resep', 'harga_beli', 'harga_jual',
        'stok_minimum', 'stok_maksimum', 'titik_pesan_ulang', 'lokasi_rak', 'kondisi_penyimpanan',
        'gambar', 'status_aktif', 'konsinyasi', 'persentase_pajak', 'catatan', 'dibuat_oleh', 'diubah_oleh'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }

    public function stokProduk(): HasMany
    {
        return $this->hasMany(StokProduk::class);
    }

    public function batchProduk(): HasMany
    {
        return $this->hasMany(BatchProduk::class);
    }

    public function pergerakanStok(): HasMany
    {
        return $this->hasMany(PergerakanStok::class);
    }

    public function satuanProduk(): HasMany
    {
        return $this->hasMany(SatuanProduk::class);
    }
}
