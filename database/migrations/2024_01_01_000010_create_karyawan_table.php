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
            $table->string('nomor_identitas', 50)->nullable();
            $table->string('npwp', 30)->nullable();
            $table->string('jabatan', 50)->nullable();
            $table->string('departemen', 50)->nullable();
            $table->enum('status_kepegawaian', ['TETAP', 'KONTRAK', 'MAGANG', 'FREELANCE'])->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->date('tanggal_resign')->nullable();
            $table->string('pendidikan', 50)->nullable();
            $table->string('nomor_lisensi', 50)->nullable();
            $table->date('kadaluarsa_lisensi')->nullable();
            $table->string('nama_bank', 100)->nullable();
            $table->string('nomor_rekening', 50)->nullable();
            $table->string('kontak_darurat_nama', 100)->nullable();
            $table->string('kontak_darurat_telepon', 20)->nullable();
            $table->string('kontak_darurat_hubungan', 50)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->text('catatan')->nullable();
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
