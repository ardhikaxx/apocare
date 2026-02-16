<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use SoftDeletes;

    protected $table = 'penjualan';
    protected $fillable = [
        'nomor_penjualan', 'client_reference', 'pelanggan_id', 'resep_id', 'tanggal_penjualan', 'jenis_penjualan',
        'status_pembayaran', 'metode_pembayaran', 'subtotal', 'jenis_diskon', 'nilai_diskon',
        'jumlah_diskon', 'jumlah_pajak', 'total_akhir', 'jumlah_bayar', 'jumlah_kembalian',
        'nomor_kartu', 'nama_pemegang_kartu', 'kode_approval', 'catatan', 'dilayani_oleh', 'dibuat_oleh', 'diubah_oleh'
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function retur(): HasMany
    {
        return $this->hasMany(ReturPenjualan::class);
    }
}
