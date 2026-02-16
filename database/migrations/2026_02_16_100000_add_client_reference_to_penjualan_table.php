<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            if (! Schema::hasColumn('penjualan', 'client_reference')) {
                $table->string('client_reference', 100)->nullable()->after('nomor_penjualan')->unique();
            }
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            if (Schema::hasColumn('penjualan', 'client_reference')) {
                $table->dropUnique('penjualan_client_reference_unique');
                $table->dropColumn('client_reference');
            }
        });
    }
};