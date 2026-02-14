<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuan_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->decimal('faktor_konversi', 10, 2)->default(1);
            $table->string('barcode', 50)->nullable();
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->boolean('default_pembelian')->default(false);
            $table->boolean('default_penjualan')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satuan_produk');
    }
};
