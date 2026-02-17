<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanHarga extends Model
{
    protected $table = 'pengaturan_harga';
    protected $fillable = [
        'kategori_default',
        'persentase_markup_default',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'persentase_markup_default' => 'decimal:2',
    ];
}
