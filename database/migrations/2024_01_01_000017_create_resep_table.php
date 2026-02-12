<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_resep', 30)->unique();
            $table->date('tanggal_resep');
            $table->foreignId('pelanggan_id')->constrained('pelanggan');
            $table->foreignId('dokter_id')->constrained('dokter');
            $table->text('diagnosa');
            $table->enum('status', ['PENDING', 'SEBAGIAN', 'SELESAI', 'BATAL']);
            $table->integer('total_item');
            $table->decimal('total_harga', 15, 2);
            $table->text('catatan');
            $table->foreignId('apoteker_id')->nullable()->constrained('pengguna');
            $table->timestamp('waktu_verifikasi')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};
