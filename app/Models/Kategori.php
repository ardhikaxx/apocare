<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use SoftDeletes;

    protected $table = 'kategori';
    protected $fillable = ['kode', 'nama', 'parent_id', 'keterangan', 'ikon', 'status_aktif', 'dibuat_oleh', 'diubah_oleh'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Kategori::class, 'parent_id');
    }

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
