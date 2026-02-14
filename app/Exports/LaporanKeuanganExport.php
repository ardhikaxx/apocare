<?php

namespace App\Exports;

use App\Exports\Concerns\WithKopExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithDrawings, ShouldAutoSize
{
    use WithKopExport;

    protected string $kopLastColumn = 'B';

    protected $totalPenjualan;
    protected $totalPembelian;
    protected $profit;

    public function __construct($totalPenjualan, $totalPembelian, $profit)
    {
        $this->totalPenjualan = $totalPenjualan;
        $this->totalPembelian = $totalPembelian;
        $this->profit = $profit;
    }

    public function collection()
    {
        return collect([
            ['Total Penjualan', (float) $this->totalPenjualan],
            ['Total Pembelian', (float) $this->totalPembelian],
            ['Profit', (float) $this->profit],
        ]);
    }

    public function headings(): array
    {
        return ['Keterangan', 'Nilai'];
    }
}
