<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('persentase_markup', 5, 2)->nullable()->after('harga_jual')->comment('Persentase markup dari harga beli');
        });

        Schema::create('pengaturan_harga', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_default')->nullable();
            $table->decimal('persentase_markup_default', 5, 2)->default(20)->comment('Markup default 20%');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('persentase_markup');
        });

        Schema::dropIfExists('pengaturan_harga');
    }
};
