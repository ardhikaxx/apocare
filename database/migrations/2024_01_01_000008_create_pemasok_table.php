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
            $table->string('kontak_person', 100)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 50)->nullable();
            $table->string('provinsi', 50)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('npwp', 30)->nullable();
            $table->integer('termin_pembayaran')->default(0);
            $table->decimal('limit_kredit', 15, 2)->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->text('catatan')->nullable();
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
