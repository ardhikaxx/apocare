<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokter extends Model
{
    use SoftDeletes;

    protected $table = 'dokter';
    protected $fillable = ['kode', 'nama', 'spesialisasi', 'nomor_sip', 'telepon', 'email', 'rumah_sakit', 'alamat', 'status_aktif', 'catatan', 'dibuat_oleh', 'diubah_oleh'];

    public function resep(): HasMany
    {
        return $this->hasMany(Resep::class);
    }
}
