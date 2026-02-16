<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            if (! Schema::hasColumn('resep', 'tahap_antrian')) {
                $table->enum('tahap_antrian', ['DITERIMA', 'DIRACIK', 'DIVERIFIKASI', 'DISERAHKAN'])
                    ->default('DITERIMA')
                    ->after('status');
            }

            if (! Schema::hasColumn('resep', 'waktu_diterima')) {
                $table->timestamp('waktu_diterima')->nullable()->after('waktu_verifikasi');
            }

            if (! Schema::hasColumn('resep', 'waktu_diracik')) {
                $table->timestamp('waktu_diracik')->nullable()->after('waktu_diterima');
            }

            if (! Schema::hasColumn('resep', 'waktu_diserahkan')) {
                $table->timestamp('waktu_diserahkan')->nullable()->after('waktu_diracik');
            }
        });

        DB::table('resep')
            ->whereNull('waktu_diterima')
            ->update(['waktu_diterima' => DB::raw('created_at')]);

        DB::table('resep')
            ->where('status', 'SELESAI')
            ->update([
                'tahap_antrian' => 'DISERAHKAN',
                'waktu_diserahkan' => DB::raw('COALESCE(waktu_diserahkan, updated_at)'),
                'waktu_verifikasi' => DB::raw('COALESCE(waktu_verifikasi, updated_at)'),
                'waktu_diracik' => DB::raw('COALESCE(waktu_diracik, updated_at)'),
            ]);

        DB::table('resep')
            ->where('status', 'SEBAGIAN')
            ->update([
                'tahap_antrian' => 'DIVERIFIKASI',
                'waktu_verifikasi' => DB::raw('COALESCE(waktu_verifikasi, updated_at)'),
                'waktu_diracik' => DB::raw('COALESCE(waktu_diracik, updated_at)'),
            ]);

        DB::table('resep')
            ->where('status', 'PENDING')
            ->update([
                'tahap_antrian' => DB::raw("COALESCE(tahap_antrian, 'DITERIMA')"),
            ]);
    }

    public function down(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            if (Schema::hasColumn('resep', 'waktu_diserahkan')) {
                $table->dropColumn('waktu_diserahkan');
            }
            if (Schema::hasColumn('resep', 'waktu_diracik')) {
                $table->dropColumn('waktu_diracik');
            }
            if (Schema::hasColumn('resep', 'waktu_diterima')) {
                $table->dropColumn('waktu_diterima');
            }
            if (Schema::hasColumn('resep', 'tahap_antrian')) {
                $table->dropColumn('tahap_antrian');
            }
        });
    }
};