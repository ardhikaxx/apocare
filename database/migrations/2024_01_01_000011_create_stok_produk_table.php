<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk');
            $table->decimal('jumlah', 10, 2)->default(0);
            $table->decimal('jumlah_reservasi', 10, 2)->default(0);
            $table->decimal('jumlah_tersedia', 10, 2)->default(0);
            $table->decimal('harga_beli_terakhir', 15, 2)->nullable();
            $table->decimal('harga_beli_rata', 15, 2)->nullable();
            $table->timestamp('terakhir_diubah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_produk');
    }
};
