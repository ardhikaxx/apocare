<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyesuaian_stok', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_penyesuaian', 30)->unique();
            $table->date('tanggal_penyesuaian');
            $table->enum('jenis_penyesuaian', ['PENAMBAHAN', 'PENGURANGAN', 'RUSAK', 'KADALUARSA', 'KOREKSI']);
            $table->enum('status', ['DRAFT', 'DISETUJUI', 'DITOLAK'])->default('DRAFT');
            $table->integer('total_item')->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pengguna');
            $table->timestamp('waktu_persetujuan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_stok');
    }
};
