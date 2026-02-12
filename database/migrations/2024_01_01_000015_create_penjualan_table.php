<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_penjualan', 30)->unique();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan');
            $table->foreignId('resep_id')->nullable()->constrained('resep');
            $table->datetime('tanggal_penjualan');
            $table->enum('jenis_penjualan', ['RETAIL', 'GROSIR', 'RESEP', 'ONLINE']);
            $table->enum('status_pembayaran', ['BELUM_BAYAR', 'SEBAGIAN', 'LUNAS']);
            $table->enum('metode_pembayaran', ['TUNAI', 'DEBIT', 'KREDIT', 'TRANSFER', 'EWALLET', 'QRIS']);
            $table->decimal('subtotal', 15, 2);
            $table->enum('jenis_diskon', ['PERSENTASE', 'NOMINAL']);
            $table->decimal('nilai_diskon', 15, 2);
            $table->decimal('jumlah_diskon', 15, 2);
            $table->decimal('jumlah_pajak', 15, 2);
            $table->decimal('total_akhir', 15, 2);
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('jumlah_kembalian', 15, 2);
            $table->string('nomor_kartu', 50);
            $table->string('nama_pemegang_kartu', 100);
            $table->string('kode_approval', 50);
            $table->text('catatan');
            $table->foreignId('dilayani_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
