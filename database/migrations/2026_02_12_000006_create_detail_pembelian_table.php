<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian');
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('satuan_produk_id')->nullable()->constrained('satuan_produk');
            $table->string('nomor_batch', 50)->nullable();
            $table->date('tanggal_produksi')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->decimal('jumlah_pesan', 10, 2)->default(0);
            $table->decimal('jumlah_terima', 10, 2)->default(0);
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
        Schema::dropIfExists('detail_pembelian');
    }
};
