<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use SoftDeletes;

    protected $table = 'pembelian';
    protected $fillable = [
        'nomor_pembelian', 'nomor_po', 'pemasok_id', 'tanggal_pembelian', 'tanggal_jatuh_tempo',
        'status', 'status_pembayaran', 'metode_pembayaran', 'subtotal', 'jenis_diskon', 'nilai_diskon',
        'jumlah_diskon', 'jumlah_pajak', 'biaya_kirim', 'biaya_lain', 'total_akhir', 'jumlah_bayar',
        'sisa_bayar', 'nomor_faktur', 'tanggal_faktur', 'nomor_faktur_pajak', 'catatan',
        'disetujui_oleh', 'waktu_persetujuan', 'diterima_oleh', 'waktu_penerimaan', 'dibuat_oleh', 'diubah_oleh'
    ];

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPembelian::class);
    }
}
