<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian');
            $table->string('nomor_pembayaran', 30)->unique();
            $table->date('tanggal_bayar');
            $table->enum('metode_pembayaran', ['TUNAI', 'TRANSFER', 'KREDIT', 'GIRO']);
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->string('nama_bank', 100)->nullable();
            $table->string('nomor_rekening', 50)->nullable();
            $table->string('nomor_giro', 50)->nullable();
            $table->date('tanggal_giro')->nullable();
            $table->string('nomor_referensi', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_pembelian');
    }
};
