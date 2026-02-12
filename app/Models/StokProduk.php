<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokProduk extends Model
{
    protected $table = 'stok_produk';
    protected $fillable = ['produk_id', 'jumlah', 'jumlah_reservasi', 'jumlah_tersedia', 'harga_beli_terakhir', 'harga_beli_rata', 'terakhir_diubah'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
