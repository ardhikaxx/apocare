<?php

namespace App\Exports;

use App\Exports\Concerns\WithKopExport;
use App\Models\Pemasok;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PemasokExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'G';

    public function collection()
    {
        return Pemasok::orderBy('nama')->get()->map(function ($item) {
            return [
                $item->kode,
                $item->nama,
                $item->kontak_person ?? '-',
                $item->telepon ?? '-',
                $item->email ?? '-',
                $item->kota ?? '-',
                $item->status_aktif ? 'Aktif' : 'Nonaktif',
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode', 'Nama', 'Kontak', 'Telepon', 'Email', 'Kota', 'Status'];
    }
}
