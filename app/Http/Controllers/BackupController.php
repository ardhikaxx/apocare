<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $files = File::files($backupPath);
        $backups = [];

        foreach ($files as $file) {
            if (str_starts_with($file->getFilename(), 'apocare_backup_')) {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'size_bytes' => $file->getSize(),
                    'created' => date('Y-m-d H:i:s', $file->getMTime()),
                    'path' => $file->getPathname(),
                ];
            }
        }

        usort($backups, function ($a, $b) {
            return strtotime($b['created']) - strtotime($a['created']);
        });

        return view('backup.index', compact('backups'));
    }

    public function download(string $filename)
    {
        $backupPath = storage_path('app/backups');
        $filePath = $backupPath . '/' . $filename;

        if (!File::exists($filePath)) {
            return redirect()->route('backup.index')->with('error', 'File backup tidak ditemukan');
        }

        return Response::download($filePath, $filename);
    }

    public function create()
    {
        try {
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
                return redirect()->route('backup.index')->with('error', 'mysqldump tidak ditemukan. Pastikan XAMPP MySQL terinstall.');
            }

            if ($password) {
                $command = sprintf(
                    '"%s" -u%s -p%s %s --single-transaction --quick --lock-tables=false -h %s -r %s',
                    $mysqldump,
                    $username,
                    $password,
                    $database,
                    $host,
                    $filepath
                );
            } else {
                $command = sprintf(
                    '"%s" -u%s %s --single-transaction --quick --lock-tables=false -h %s -r %s',
                    $mysqldump,
                    $username,
                    $database,
                    $host,
                    $filepath
                );
            }

            exec($command, $output, $return);

            if ($return !== 0 || !File::exists($filepath)) {
                $errorMsg = isset($output[0]) ? $output[0] : 'Unknown error';
                return redirect()->route('backup.index')->with('error', 'Backup gagal. Error: ' . $errorMsg);
            }

            $this->cleanupOldBackups($backupPath);

            return redirect()->route('backup.index')->with('success', 'Backup database berhasil dibuat: ' . $filename);
            
        } catch (\Exception $e) {
            return redirect()->route('backup.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function findMySqlDump(): ?string
    {
        $paths = [
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp\bin\mysql\mysql5.7.31\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql5.7.31\bin\mysqldump.exe',
            'C:\laragon\bin\mysql\mysql-5.7\bin\mysqldump.exe',
            'C:\laragon\bin\mysql\mysql8.0\bin\mysqldump.exe',
            'mysqldump',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        exec('where mysqldump 2>NUL', $output, $return);
        if ($return === 0 && !empty($output[0])) {
            return trim($output[0]);
        }

        return null;
    }

    private function cleanupOldBackups(string $path, int $keep = 30)
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
        }
    }

    public function destroy(string $filename)
    {
        $backupPath = storage_path('app/backups');
        $filePath = $backupPath . '/' . $filename;

        if (!File::exists($filePath)) {
            return redirect()->route('backup.index')->with('error', 'File backup tidak ditemukan');
        }

        File::delete($filePath);

        return redirect()->route('backup.index')->with('success', 'Backup berhasil dihapus');
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
