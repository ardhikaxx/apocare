<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penyesuaian_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyesuaian_id')->constrained('penyesuaian_stok');
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('batch_id')->nullable()->constrained('batch_produk');
            $table->decimal('jumlah_sistem', 10, 2)->default(0);
            $table->decimal('jumlah_aktual', 10, 2)->default(0);
            $table->decimal('selisih', 10, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('total_nilai', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penyesuaian_stok');
    }
};
