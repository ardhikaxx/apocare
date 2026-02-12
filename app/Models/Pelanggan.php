<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use SoftDeletes;

    protected $table = 'pelanggan';
    protected $fillable = [
        'kode', 'nama', 'jenis_pelanggan', 'jenis_identitas', 'nomor_identitas', 'jenis_kelamin',
        'tanggal_lahir', 'telepon', 'email', 'alamat', 'kota', 'provinsi', 'kode_pos',
        'persentase_diskon', 'limit_kredit', 'termin_pembayaran', 'total_pembelian',
        'tanggal_beli_terakhir', 'status_aktif', 'catatan', 'dibuat_oleh', 'diubah_oleh'
    ];

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class);
    }

    public function resep(): HasMany
    {
        return $this->hasMany(Resep::class);
    }
}
