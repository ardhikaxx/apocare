<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailReturPenjualan extends Model
{
    protected $table = 'detail_retur_penjualan';

    protected $fillable = [
        'retur_id', 'detail_penjualan_id', 'produk_id', 'batch_id', 'jumlah', 'harga_satuan',
        'persentase_pajak', 'jumlah_pajak', 'subtotal', 'total', 'alasan'
    ];

    public function retur(): BelongsTo
    {
        return $this->belongsTo(ReturPenjualan::class, 'retur_id');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
