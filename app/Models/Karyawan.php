<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use SoftDeletes;

    protected $table = 'karyawan';
    protected $fillable = [
        'pengguna_id', 'nomor_karyawan', 'nomor_identitas', 'npwp', 'jabatan', 'departemen',
        'status_kepegawaian', 'tanggal_bergabung', 'tanggal_resign', 'pendidikan', 'nomor_lisensi',
        'kadaluarsa_lisensi', 'nama_bank', 'nomor_rekening', 'kontak_darurat_nama',
        'kontak_darurat_telepon', 'kontak_darurat_hubungan', 'status_aktif', 'catatan',
        'dibuat_oleh', 'diubah_oleh'
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
