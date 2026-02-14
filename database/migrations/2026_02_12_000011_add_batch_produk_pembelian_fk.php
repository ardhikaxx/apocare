<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batch_produk', function (Blueprint $table) {
            $table->foreign('pembelian_id')
                ->references('id')
                ->on('pembelian')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('batch_produk', function (Blueprint $table) {
            $table->dropForeign(['pembelian_id']);
        });
    }
};
