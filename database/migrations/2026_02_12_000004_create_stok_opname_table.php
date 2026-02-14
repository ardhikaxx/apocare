<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_opname', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_opname', 30)->unique();
            $table->date('tanggal_opname');
            $table->enum('status', ['DRAFT', 'PROSES', 'SELESAI', 'DISETUJUI'])->default('DRAFT');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori');
            $table->integer('total_item_dihitung')->default(0);
            $table->integer('total_item_cocok')->default(0);
            $table->integer('total_item_selisih')->default(0);
            $table->decimal('total_nilai_selisih', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pengguna');
            $table->timestamp('waktu_persetujuan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opname');
    }
};
