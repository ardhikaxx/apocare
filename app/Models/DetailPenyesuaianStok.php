<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenyesuaianStok extends Model
{
    protected $table = 'detail_penyesuaian_stok';

    protected $fillable = [
        'penyesuaian_id', 'produk_id', 'batch_id', 'jumlah_sistem', 'jumlah_aktual',
        'selisih', 'harga_satuan', 'total_nilai', 'catatan'
    ];

    public function penyesuaian(): BelongsTo
    {
        return $this->belongsTo(PenyesuaianStok::class, 'penyesuaian_id');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(BatchProduk::class, 'batch_id');
    }
}
