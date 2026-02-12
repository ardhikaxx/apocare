<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk');
            $table->string('nomor_batch', 50);
            $table->date('tanggal_produksi');
            $table->date('tanggal_kadaluarsa');
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_beli', 15, 2);
            $table->foreignId('pemasok_id')->constrained('pemasok');
            $table->foreignId('pembelian_id')->nullable()->constrained('pembelian');
            $table->boolean('sudah_kadaluarsa')->default(false);
            $table->text('catatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_produk');
    }
};
