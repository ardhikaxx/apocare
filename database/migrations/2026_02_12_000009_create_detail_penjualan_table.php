<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan');
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('satuan_produk_id')->nullable()->constrained('satuan_produk');
            $table->foreignId('batch_id')->nullable()->constrained('batch_produk');
            $table->decimal('jumlah', 10, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('persentase_diskon', 5, 2)->default(0);
            $table->decimal('jumlah_diskon', 15, 2)->default(0);
            $table->decimal('persentase_pajak', 5, 2)->default(0);
            $table->decimal('jumlah_pajak', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
