<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('barcode', 50)->unique();
            $table->string('nama', 200);
            $table->string('nama_generik', 200);
            $table->foreignId('kategori_id')->constrained('kategori');
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->string('produsen', 100);
            $table->text('keterangan');
            $table->enum('jenis_produk', ['Obat', 'Alkes', 'Vitamin', 'Kosmetik', 'Umum']);
            $table->enum('golongan_obat', ['Obat Bebas', 'Obat Bebas Terbatas', 'Obat Keras', 'Narkotika', 'Psikotropika']);
            $table->boolean('perlu_resep')->default(false);
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->integer('stok_minimum')->default(0);
            $table->integer('stok_maksimum')->default(0);
            $table->integer('titik_pesan_ulang')->default(0);
            $table->string('lokasi_rak', 20);
            $table->text('kondisi_penyimpanan');
            $table->string('gambar', 255);
            $table->boolean('status_aktif')->default(true);
            $table->boolean('konsinyasi')->default(false);
            $table->decimal('persentase_pajak', 5, 2)->default(0);
            $table->text('catatan');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
