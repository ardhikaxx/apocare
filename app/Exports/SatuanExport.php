<?php

namespace App\Exports;

use App\Exports\Concerns\WithKopExport;
use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SatuanExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'D';

    public function collection()
    {
        return Satuan::orderBy('kode')->get()->map(function ($item) {
            return [
                $item->kode,
                $item->nama,
                $item->keterangan ?? '-',
                $item->status_aktif ? 'Aktif' : 'Nonaktif',
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode', 'Nama', 'Keterangan', 'Status'];
    }
}
