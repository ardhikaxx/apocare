<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailResep extends Model
{
    protected $table = 'detail_resep';
    protected $fillable = [
        'resep_id', 'produk_id', 'dosis', 'frekuensi', 'durasi', 'cara_pakai', 'jumlah_resep',
        'jumlah_diberikan', 'harga_satuan', 'total', 'instruksi', 'catatan'
    ];

    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
