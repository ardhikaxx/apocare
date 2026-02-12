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
            $table->string('nomor_po', 30);
            $table->foreignId('pemasok_id')->constrained('pemasok');
            $table->date('tanggal_pembelian');
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['DRAFT', 'DIPESAN', 'SEBAGIAN', 'DITERIMA', 'SELESAI', 'BATAL']);
            $table->enum('status_pembayaran', ['BELUM_BAYAR', 'SEBAGIAN', 'LUNAS']);
            $table->enum('metode_pembayaran', ['TUNAI', 'TRANSFER', 'KREDIT', 'GIRO']);
            $table->decimal('subtotal', 15, 2);
            $table->enum('jenis_diskon', ['PERSENTASE', 'NOMINAL']);
            $table->decimal('nilai_diskon', 15, 2);
            $table->decimal('jumlah_diskon', 15, 2);
            $table->decimal('jumlah_pajak', 15, 2);
            $table->decimal('biaya_kirim', 15, 2);
            $table->decimal('biaya_lain', 15, 2);
            $table->decimal('total_akhir', 15, 2);
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('sisa_bayar', 15, 2);
            $table->string('nomor_faktur', 50);
            $table->date('tanggal_faktur');
            $table->string('nomor_faktur_pajak', 50);
            $table->text('catatan');
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
