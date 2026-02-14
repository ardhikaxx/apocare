<?php

namespace App\Exports;

use App\Exports\Concerns\WithKopExport;
use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdukExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'G';

    public function collection()
    {
        return Produk::with(['kategori', 'satuan'])->orderBy('nama')->get()->map(function ($item) {
            return [
                $item->kode,
                $item->nama,
                $item->kategori ? $item->kategori->nama : '-',
                $item->satuan ? $item->satuan->nama : '-',
                $item->harga_jual,
                $item->stok_minimum,
                $item->status_aktif ? 'Aktif' : 'Nonaktif',
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode', 'Nama', 'Kategori', 'Satuan', 'Harga Jual', 'Stok Minimum', 'Status'];
    }
}
