<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('batch_produk')) {
            return;
        }

        Schema::create('batch_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk');
            $table->string('nomor_batch', 50);
            $table->date('tanggal_produksi')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->decimal('jumlah', 10, 2)->default(0);
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->foreignId('pemasok_id')->nullable()->constrained('pemasok');
            $table->unsignedBigInteger('pembelian_id')->nullable()->index();
            $table->boolean('sudah_kadaluarsa')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_produk');
    }
};
