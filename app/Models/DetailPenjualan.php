<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    protected $fillable = [
        'penjualan_id', 'produk_id', 'satuan_produk_id', 'batch_id', 'jumlah', 'harga_satuan',
        'persentase_diskon', 'jumlah_diskon', 'persentase_pajak', 'jumlah_pajak', 'subtotal',
        'total', 'catatan'
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
