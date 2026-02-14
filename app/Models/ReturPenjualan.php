<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturPenjualan extends Model
{
    use SoftDeletes;

    protected $table = 'retur_penjualan';

    protected $fillable = [
        'nomor_retur', 'penjualan_id', 'pelanggan_id', 'tanggal_retur', 'alasan',
        'status', 'subtotal', 'jumlah_pajak', 'total', 'metode_refund',
        'jumlah_refund', 'catatan', 'disetujui_oleh', 'waktu_persetujuan',
        'dibuat_oleh', 'diubah_oleh'
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailReturPenjualan::class, 'retur_id');
    }
}
