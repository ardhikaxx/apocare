<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HakAkses extends Model
{
    protected $table = 'hak_akses';
    protected $fillable = ['nama', 'kode', 'modul'];

    public function peran(): BelongsToMany
    {
        return $this->belongsToMany(Peran::class, 'peran_hak_akses', 'permission_id', 'role_id');
    }
}
