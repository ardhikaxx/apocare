<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenis_pelanggan', ['REGULAR', 'RESELLER', 'KESEHATAN', 'PERUSAHAAN']);
            $table->enum('jenis_identitas', ['KTP', 'SIM', 'PASSPORT']);
            $table->string('nomor_identitas', 50);
            $table->enum('jenis_kelamin', ['PRIA', 'WANITA']);
            $table->date('tanggal_lahir');
            $table->string('telepon', 20);
            $table->string('email', 100);
            $table->text('alamat');
            $table->string('kota', 50);
            $table->string('provinsi', 50);
            $table->string('kode_pos', 10);
            $table->decimal('persentase_diskon', 5, 2)->default(0);
            $table->decimal('limit_kredit', 15, 2)->default(0);
            $table->integer('termin_pembayaran')->default(0);
            $table->decimal('total_pembelian', 15, 2)->default(0);
            $table->date('tanggal_beli_terakhir');
            $table->boolean('status_aktif')->default(true);
            $table->text('catatan');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
