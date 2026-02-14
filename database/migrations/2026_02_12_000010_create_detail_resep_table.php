<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_resep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_id')->constrained('resep');
            $table->foreignId('produk_id')->constrained('produk');
            $table->string('dosis', 100)->nullable();
            $table->string('frekuensi', 100)->nullable();
            $table->string('durasi', 100)->nullable();
            $table->string('cara_pakai', 150)->nullable();
            $table->decimal('jumlah_resep', 10, 2)->default(0);
            $table->decimal('jumlah_diberikan', 10, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('instruksi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_resep');
    }
};
