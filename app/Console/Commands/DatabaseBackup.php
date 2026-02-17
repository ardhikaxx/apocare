<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseBackup extends Command
{
    protected $signature = 'app:database-backup {--keep=30 : Jumlah backup yang akan disimpan}';
    protected $description = 'Backup database MySQL dan simpan ke storage/backups';

    public function handle()
    {
        $this->info('Memulai backup database...');
        
        $backupPath = storage_path('app/backups');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $filename = 'apocare_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupPath . '/' . $filename;

        $mysqldump = $this->findMySqlDump();
        
        if (!$mysqldump) {
            $this->error('mysqldump tidak ditemukan! Pastikan MySQL sudah terinstall.');
            return Command::FAILURE;
        }

        if ($password) {
            $command = sprintf(
                '"%s" -u%s -p%s %s --single-transaction --quick --lock-tables=false -h %s > %s',
                $mysqldump,
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($host),
                escapeshellarg($filepath)
            );
        } else {
            $command = sprintf(
                '"%s" -u%s %s --single-transaction --quick --lock-tables=false -h %s > %s',
                $mysqldump,
                escapeshellarg($username),
                escapeshellarg($database),
                escapeshellarg($host),
                escapeshellarg($filepath)
            );
        }

        exec($command, $output, $return);

        if ($return !== 0) {
            $this->error('Gagal membuat backup database!');
            return Command::FAILURE;
        }

        $this->info('Backup berhasil dibuat: ' . $filename);

        $keep = (int) $this->option('keep');
        $this->cleanupOldBackups($backupPath, $keep);

        return Command::SUCCESS;
    }

    private function findMySqlDump(): ?string
    {
        $paths = [
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp\bin\mysql\mysql5.7.31\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql5.7.31\bin\mysqldump.exe',
            'C:\laragon\bin\mysql\mysql-5.7\bin\mysqldump.exe',
            'mysqldump',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        exec('where mysqldump', $output, $return);
        if ($return === 0 && !empty($output[0])) {
            return trim($output[0]);
        }

        return null;
    }

    private function cleanupOldBackups(string $path, int $keep)
    {
        $files = File::files($path);
        $backups = array_filter($files, function ($file) {
            return str_starts_with($file->getFilename(), 'apocare_backup_');
        });

        usort($backups, function ($a, $b) {
            return $b->getMTime() - $a->getMTime();
        });

        $toDelete = array_slice($backups, $keep);
        foreach ($toDelete as $file) {
            File::delete($file->getPathname());
            $this->line('Deleted: ' . $file->getFilename());
        }

        if (count($toDelete) > 0) {
            $this->info(count($toDelete) . ' backup lama berhasil dihapus');
        }
    }
}
