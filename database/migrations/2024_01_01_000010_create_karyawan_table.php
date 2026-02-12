<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna');
            $table->string('nomor_karyawan', 20)->unique();
            $table->string('nomor_identitas', 50);
            $table->string('npwp', 30);
            $table->string('jabatan', 50);
            $table->string('departemen', 50);
            $table->enum('status_kepegawaian', ['TETAP', 'KONTRAK', 'MAGANG', 'FREELANCE']);
            $table->date('tanggal_bergabung');
            $table->date('tanggal_resign')->nullable();
            $table->string('pendidikan', 50);
            $table->string('nomor_lisensi', 50);
            $table->date('kadaluarsa_lisensi');
            $table->string('nama_bank', 100);
            $table->string('nomor_rekening', 50);
            $table->string('kontak_darurat_nama', 100);
            $table->string('kontak_darurat_telepon', 20);
            $table->string('kontak_darurat_hubungan', 50);
            $table->boolean('status_aktif')->default(true);
            $table->text('catatan');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
