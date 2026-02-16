<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resep extends Model
{
    use SoftDeletes;

    public const TAHAP_DITERIMA = 'DITERIMA';
    public const TAHAP_DIRACIK = 'DIRACIK';
    public const TAHAP_DIVERIFIKASI = 'DIVERIFIKASI';
    public const TAHAP_DISERAHKAN = 'DISERAHKAN';

    public const TAHAP_URUT = [
        self::TAHAP_DITERIMA,
        self::TAHAP_DIRACIK,
        self::TAHAP_DIVERIFIKASI,
        self::TAHAP_DISERAHKAN,
    ];

    protected $table = 'resep';
    protected $fillable = [
        'nomor_resep', 'tanggal_resep', 'pelanggan_id', 'dokter_id', 'diagnosa', 'status', 'tahap_antrian',
        'total_item', 'total_harga', 'catatan', 'apoteker_id', 'waktu_verifikasi', 'waktu_diterima',
        'waktu_diracik', 'waktu_diserahkan', 'dibuat_oleh', 'diubah_oleh'
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

    public function nextTahap(): ?string
    {
        $index = array_search($this->tahap_antrian, self::TAHAP_URUT, true);
        if ($index === false) {
            return self::TAHAP_DITERIMA;
        }

        return self::TAHAP_URUT[$index + 1] ?? null;
    }
}