<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPembelian extends Model
{
    protected $table = 'pembayaran_pembelian';

    protected $fillable = [
        'pembelian_id', 'nomor_pembayaran', 'tanggal_bayar', 'metode_pembayaran',
        'jumlah', 'nama_bank', 'nomor_rekening', 'nomor_giro', 'tanggal_giro',
        'nomor_referensi', 'catatan', 'dibuat_oleh'
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }
}
