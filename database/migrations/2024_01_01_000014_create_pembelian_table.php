<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pembelian', 30)->unique();
            $table->string('nomor_po', 30)->nullable();
            $table->foreignId('pemasok_id')->constrained('pemasok');
            $table->date('tanggal_pembelian');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->enum('status', ['DRAFT', 'DIPESAN', 'SEBAGIAN', 'DITERIMA', 'SELESAI', 'BATAL'])->default('DRAFT');
            $table->enum('status_pembayaran', ['BELUM_BAYAR', 'SEBAGIAN', 'LUNAS'])->default('BELUM_BAYAR');
            $table->enum('metode_pembayaran', ['TUNAI', 'TRANSFER', 'KREDIT', 'GIRO'])->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->enum('jenis_diskon', ['PERSENTASE', 'NOMINAL'])->nullable();
            $table->decimal('nilai_diskon', 15, 2)->default(0);
            $table->decimal('jumlah_diskon', 15, 2)->default(0);
            $table->decimal('jumlah_pajak', 15, 2)->default(0);
            $table->decimal('biaya_kirim', 15, 2)->default(0);
            $table->decimal('biaya_lain', 15, 2)->default(0);
            $table->decimal('total_akhir', 15, 2)->default(0);
            $table->decimal('jumlah_bayar', 15, 2)->default(0);
            $table->decimal('sisa_bayar', 15, 2)->default(0);
            $table->string('nomor_faktur', 50)->nullable();
            $table->date('tanggal_faktur')->nullable();
            $table->string('nomor_faktur_pajak', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pengguna');
            $table->timestamp('waktu_persetujuan')->nullable();
            $table->foreignId('diterima_oleh')->nullable()->constrained('pengguna');
            $table->timestamp('waktu_penerimaan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
