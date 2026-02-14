<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailStokOpname extends Model
{
    protected $table = 'detail_stok_opname';

    protected $fillable = [
        'opname_id', 'produk_id', 'batch_id', 'jumlah_sistem', 'jumlah_hitung',
        'selisih', 'harga_satuan', 'total_nilai_selisih', 'status',
        'dihitung_oleh', 'waktu_hitung', 'catatan'
    ];

    public function opname(): BelongsTo
    {
        return $this->belongsTo(StokOpname::class, 'opname_id');
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
