<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retur_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_retur', 30)->unique();
            $table->foreignId('penjualan_id')->constrained('penjualan');
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan');
            $table->dateTime('tanggal_retur');
            $table->text('alasan')->nullable();
            $table->enum('status', ['PENDING', 'DISETUJUI', 'DITOLAK', 'SELESAI'])->default('PENDING');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('jumlah_pajak', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('metode_refund', ['TUNAI', 'TRANSFER', 'NOTA_KREDIT'])->nullable();
            $table->decimal('jumlah_refund', 15, 2)->default(0);
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
        Schema::dropIfExists('retur_penjualan');
    }
};
