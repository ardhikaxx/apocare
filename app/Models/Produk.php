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
        'gambar', 'status_aktif', 'konsinyasi', 'persentase_pajak', 'catatan', 'is_favorit',
        'no_ijin_edar', 'is_expired', 'tanggal_expired', 'persentase_markup',
        'dibuat_oleh', 'diubah_oleh'
    ];

    protected $casts = [
        'is_favorit' => 'boolean',
        'perlu_resep' => 'boolean',
        'status_aktif' => 'boolean',
        'konsinyasi' => 'boolean',
        'is_expired' => 'boolean',
        'tanggal_expired' => 'date',
        'persentase_markup' => 'decimal:2',
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

    public function getHargaJualOtomatis(): float
    {
        $markup = $this->persentase_markup ?? 20;
        return $this->harga_beli + ($this->harga_beli * $markup / 100);
    }

    public function getSelisihHarga(): float
    {
        return $this->harga_jual - $this->harga_beli;
    }

    public function getMarginPersen(): float
    {
        if ($this->harga_beli == 0) return 0;
        return (($this->harga_jual - $this->harga_beli) / $this->harga_beli) * 100;
    }
}
