<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_stok_opname', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opname_id')->constrained('stok_opname');
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('batch_id')->nullable()->constrained('batch_produk');
            $table->decimal('jumlah_sistem', 10, 2)->default(0);
            $table->decimal('jumlah_hitung', 10, 2)->default(0);
            $table->decimal('selisih', 10, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('total_nilai_selisih', 15, 2)->default(0);
            $table->enum('status', ['COCOK', 'LEBIH', 'KURANG'])->default('COCOK');
            $table->foreignId('dihitung_oleh')->nullable()->constrained('pengguna');
            $table->timestamp('waktu_hitung')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_stok_opname');
    }
};
