<?php

namespace App\Exports;

use App\Exports\Concerns\WithKopExport;
use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PelangganExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'F';

    public function collection()
    {
        return Pelanggan::orderBy('nama')->get()->map(function ($item) {
            return [
                $item->kode,
                $item->nama,
                $item->jenis_pelanggan,
                $item->telepon ?? '-',
                $item->email ?? '-',
                $item->status_aktif ? 'Aktif' : 'Nonaktif',
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode', 'Nama', 'Kategori', 'Telepon', 'Email', 'Status'];
    }
}
