<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturPembelian extends Model
{
    use SoftDeletes;

    protected $table = 'retur_pembelian';

    protected $fillable = [
        'nomor_retur', 'pembelian_id', 'pemasok_id', 'tanggal_retur', 'alasan',
        'status', 'subtotal', 'jumlah_pajak', 'total', 'metode_refund',
        'jumlah_refund', 'catatan', 'dibuat_oleh', 'diubah_oleh'
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailReturPembelian::class, 'retur_id');
    }
}
