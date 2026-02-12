<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peran extends Model
{
    use SoftDeletes;

    protected $table = 'peran';
    protected $fillable = ['nama', 'keterangan'];

    public function hakAkses(): BelongsToMany
    {
        return $this->belongsToMany(HakAkses::class, 'peran_hak_akses', 'role_id', 'permission_id');
    }

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class);
    }
}
