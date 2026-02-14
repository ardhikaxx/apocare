<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Peran extends Model
{

    protected $table = 'peran';
    protected $fillable = ['nama', 'keterangan'];

    public function hakAkses(): BelongsToMany
    {
        return $this->belongsToMany(HakAkses::class, 'peran_hak_akses', 'role_id', 'permission_id');
    }

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'role_id');
    }
}
