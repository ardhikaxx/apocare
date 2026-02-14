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

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'J';

    protected $penjualan;

    public function __construct($penjualan)
    {
        $this->penjualan = $penjualan;
    }

    public function collection()
    {
        return $this->penjualan;
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Tanggal',
            'Pelanggan',
            'Jenis',
            'Metode',
            'Status',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Total',
        ];
    }

    public function map($item): array
    {
        $tanggal = $item->tanggal_penjualan
            ? Carbon::parse($item->tanggal_penjualan)->format('Y-m-d H:i')
            : '-';

        return [
            $item->nomor_penjualan,
            $tanggal,
            $item->pelanggan->nama ?? 'Umum',
            $item->jenis_penjualan ?? '-',
            $item->metode_pembayaran ?? '-',
            $item->status_pembayaran ?? '-',
            (float) $item->subtotal,
            (float) $item->jumlah_diskon,
            (float) $item->jumlah_pajak,
            (float) $item->total_akhir,
        ];
    }
}
