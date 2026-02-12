<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchProduk extends Model
{
    protected $table = 'batch_produk';
    protected $fillable = ['produk_id', 'nomor_batch', 'tanggal_produksi', 'tanggal_kadaluarsa', 'jumlah', 'harga_beli', 'pemasok_id', 'pembelian_id', 'sudah_kadaluarsa', 'catatan'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class);
    }
}
