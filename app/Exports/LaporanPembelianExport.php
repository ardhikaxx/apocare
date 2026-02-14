<?php

namespace App\Exports;

use App\Exports\Concerns\WithKopExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPembelianExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'K';

    protected $pembelian;

    public function __construct($pembelian)
    {
        $this->pembelian = $pembelian;
    }

    public function collection()
    {
        return $this->pembelian;
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Nomor PO',
            'Tanggal',
            'Pemasok',
            'Status',
            'Status Pembayaran',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Total',
            'Sisa Bayar',
        ];
    }

    public function map($item): array
    {
        $tanggal = $item->tanggal_pembelian
            ? Carbon::parse($item->tanggal_pembelian)->format('Y-m-d')
            : '-';

        return [
            $item->nomor_pembelian,
            $item->nomor_po ?? '-',
            $tanggal,
            $item->pemasok->nama ?? '-',
            $item->status ?? '-',
            $item->status_pembayaran ?? '-',
            (float) $item->subtotal,
            (float) $item->jumlah_diskon,
            (float) $item->jumlah_pajak,
            (float) $item->total_akhir,
            (float) $item->sisa_bayar,
        ];
    }
}
