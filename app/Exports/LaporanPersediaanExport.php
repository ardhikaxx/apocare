<?php

namespace App\Exports;

use App\Exports\Concerns\WithKopExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPersediaanExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'G';

    protected $stok;

    public function __construct($stok)
    {
        $this->stok = $stok;
    }

    public function collection()
    {
        return $this->stok;
    }

    public function headings(): array
    {
        return [
            'Kode Produk',
            'Nama Produk',
            'Kategori',
            'Satuan',
            'Stok',
            'Harga Beli',
            'Nilai Persediaan',
        ];
    }

    public function map($item): array
    {
        $produk = $item->produk;
        $hargaBeli = (float) ($produk->harga_beli ?? 0);
        $jumlah = (float) ($item->jumlah ?? 0);

        return [
            $produk->kode ?? '-',
            $produk->nama ?? '-',
            $produk->kategori->nama ?? '-',
            $produk->satuan->nama ?? '-',
            $jumlah,
            $hargaBeli,
            $jumlah * $hargaBeli,
        ];
    }
}
