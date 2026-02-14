<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pergerakan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('batch_id')->nullable()->constrained('batch_produk');
            $table->enum('jenis_pergerakan', ['MASUK', 'KELUAR', 'PENYESUAIAN', 'RETUR', 'KADALUARSA', 'RUSAK']);
            $table->string('tipe_referensi', 50)->nullable();
            $table->bigInteger('id_referensi')->nullable();
            $table->decimal('jumlah', 10, 2)->default(0);
            $table->decimal('jumlah_sebelum', 10, 2)->default(0);
            $table->decimal('jumlah_sesudah', 10, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pergerakan_stok');
    }
};
