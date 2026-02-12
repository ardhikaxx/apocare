<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->foreignId('parent_id')->nullable()->constrained('kategori');
            $table->text('keterangan')->nullable();
            $table->string('ikon', 50)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
