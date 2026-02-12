<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemasok', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->string('kontak_person', 100);
            $table->string('telepon', 20);
            $table->string('email', 100);
            $table->text('alamat');
            $table->string('kota', 50);
            $table->string('provinsi', 50);
            $table->string('kode_pos', 10);
            $table->string('npwp', 30);
            $table->integer('termin_pembayaran')->default(0);
            $table->decimal('limit_kredit', 15, 2)->default(0);
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
        Schema::dropIfExists('pemasok');
    }
};
