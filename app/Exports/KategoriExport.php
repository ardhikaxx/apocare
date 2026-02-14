<?php

namespace App\Exports;

use App\Exports\Concerns\WithKopExport;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategoriExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'D';

    public function collection()
    {
        return Kategori::with('parent')->orderBy('kode')->get()->map(function ($item) {
            return [
                $item->kode,
                $item->nama,
                $item->parent ? $item->parent->nama : '-',
                $item->status_aktif ? 'Aktif' : 'Nonaktif',
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode', 'Nama', 'Parent', 'Status'];
    }
}
