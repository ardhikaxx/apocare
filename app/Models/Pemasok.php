<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemasok extends Model
{
    use SoftDeletes;

    protected $table = 'pemasok';
    protected $fillable = ['kode', 'nama', 'kontak_person', 'telepon', 'email', 'alamat', 'kota', 'provinsi', 'kode_pos', 'npwp', 'termin_pembayaran', 'limit_kredit', 'status_aktif', 'catatan', 'dibuat_oleh', 'diubah_oleh'];

    public function purchases()
    {
        return $this->hasMany(Pembelian::class);
    }
}
