<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $fillable = ['kode', 'nama', 'keterangan', 'status_aktif'];

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
