<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PergerakanStok extends Model
{
    protected $table = 'pergerakan_stok';
    protected $fillable = ['produk_id', 'batch_id', 'jenis_pergerakan', 'tipe_referensi', 'id_referensi', 'jumlah', 'jumlah_sebelum', 'jumlah_sesudah', 'harga_satuan', 'catatan', 'dibuat_oleh'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(BatchProduk::class);
    }
}
