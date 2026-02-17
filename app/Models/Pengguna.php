<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $table = 'pengguna';
    protected $fillable = [
        'nama', 'email', 'username', 'password', 'role_id',
        'telepon', 'alamat', 'foto', 'status_aktif', 'login_terakhir',
        'last_login_at', 'last_login_ip', 'is_online',
        'dibuat_oleh', 'diubah_oleh'
    ];
    protected $hidden = ['password'];

    protected $casts = [
        'status_aktif' => 'boolean',
        'login_terakhir' => 'datetime',
        'last_login_at' => 'datetime',
        'is_online' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Peran::class, 'role_id');
    }

    public function peran()
    {
        return $this->role();
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'pengguna_id');
    }

    public function purchases()
    {
        return $this->hasMany(Pembelian::class, 'dibuat_oleh');
    }

    public function sales()
    {
        return $this->hasMany(Penjualan::class, 'dibuat_oleh');
    }
}
