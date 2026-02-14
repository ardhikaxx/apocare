<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SatuanProduk extends Model
{
    protected $table = 'satuan_produk';

    protected $fillable = [
        'produk_id', 'satuan_id', 'faktor_konversi', 'barcode',
        'harga_beli', 'harga_jual', 'default_pembelian', 'default_penjualan'
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
