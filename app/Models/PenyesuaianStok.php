<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenyesuaianStok extends Model
{
    use SoftDeletes;

    protected $table = 'penyesuaian_stok';

    protected $fillable = [
        'nomor_penyesuaian', 'tanggal_penyesuaian', 'jenis_penyesuaian', 'status',
        'total_item', 'catatan', 'disetujui_oleh', 'waktu_persetujuan',
        'dibuat_oleh', 'diubah_oleh'
    ];

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPenyesuaianStok::class, 'penyesuaian_id');
    }
}
