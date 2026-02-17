<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\BatchProduk;

class AutoNonaktifExpired extends Command
{
    protected $signature = 'app:auto-nonaktif-expired';
    protected $description = 'Nonaktifkan produk yang sudah expired secara otomatis';

    public function handle()
    {
        $this->info('Memulai proses nonaktif produk expired...');

        $today = now()->toDateString();

        $expiredBatches = BatchProduk::where('tanggal_kadaluarsa', '<', $today)
            ->where('sudah_kadaluarsa', false)
            ->get();

        if ($expiredBatches->isEmpty()) {
            $this->info('Tidak ada produk expired untuk dinonaktifkan.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($expiredBatches as $batch) {
            $batch->update(['sudah_kadaluarsa' => true]);
            $batch->produk->update([
                'is_expired' => true, 
                'status_aktif' => false, 
                'tanggal_expired' => $batch->tanggal_kadaluarsa
            ]);
            $count++;
            $this->line("Produk expired: {$batch->produk->nama} (Batch: {$batch->nomor_batch})");
        }

        $this->info("Berhasil nonaktifkan {$count} produk yang sudah expired.");
        return Command::SUCCESS;
    }
}
