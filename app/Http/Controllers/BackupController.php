<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

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
        $exitCode = Artisan::call('app:database-backup');
        
        if ($exitCode === 0) {
            return redirect()->route('backup.index')->with('success', 'Backup database berhasil dibuat');
        }
        
        return redirect()->route('backup.index')->with('error', 'Backup database gagal');
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
