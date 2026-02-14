<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokOpname extends Model
{
    use SoftDeletes;

    protected $table = 'stok_opname';

    protected $fillable = [
        'nomor_opname', 'tanggal_opname', 'status', 'kategori_id',
        'total_item_dihitung', 'total_item_cocok', 'total_item_selisih',
        'total_nilai_selisih', 'catatan', 'disetujui_oleh', 'waktu_persetujuan',
        'dibuat_oleh', 'diubah_oleh'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailStokOpname::class, 'opname_id');
    }
}
