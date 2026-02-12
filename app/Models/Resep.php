<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resep extends Model
{
    use SoftDeletes;

    protected $table = 'resep';
    protected $fillable = [
        'nomor_resep', 'tanggal_resep', 'pelanggan_id', 'dokter_id', 'diagnosa', 'status',
        'total_item', 'total_harga', 'catatan', 'apoteker_id', 'waktu_verifikasi', 'dibuat_oleh', 'diubah_oleh'
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailResep::class);
    }
}
