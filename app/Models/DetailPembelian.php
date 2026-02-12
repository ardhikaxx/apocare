<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelian';
    protected $fillable = [
        'pembelian_id', 'produk_id', 'satuan_produk_id', 'nomor_batch', 'tanggal_produksi',
        'tanggal_kadaluarsa', 'jumlah_pesan', 'jumlah_terima', 'harga_satuan', 'persentase_diskon',
        'jumlah_diskon', 'persentase_pajak', 'jumlah_pajak', 'subtotal', 'total', 'catatan'
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
