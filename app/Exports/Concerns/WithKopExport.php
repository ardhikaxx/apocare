<?php

namespace App\Exports\Concerns;

use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

trait WithKopExport
{
    protected string $kopTitle = 'APOCARE';
    protected string $kopSubtitle = 'Integrated Pharmacy Management System';
    protected int $kopDataStartRow = 6;

    public function startCell(): string
    {
        return 'A' . $this->kopDataStartRow;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $this->kopLastColumn();

                $sheet->mergeCells('B1:' . $lastColumn . '1');
                $sheet->mergeCells('B2:' . $lastColumn . '2');

                $sheet->setCellValue('B1', $this->kopTitle);
                $sheet->setCellValue('B2', $this->kopSubtitle);

                $sheet->getStyle('B1:' . $lastColumn . '1')->getFont()->setBold(true)->setSize(18);
                $sheet->getStyle('B2:' . $lastColumn . '2')->getFont()->setSize(12);

                $sheet->getStyle('B1:' . $lastColumn . '2')
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getRowDimension(1)->setRowHeight(26);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(6);

                $sheet->mergeCells('A4:' . $lastColumn . '4');
                $sheet->getStyle('A4:' . $lastColumn . '4')
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getColumnDimension('A')->setWidth(12);
            },
        ];
    }

    public function drawings()
    {
        $path = $this->kopLogoPath();
        if (!$path || !file_exists($path)) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Apocare Logo');
        $drawing->setDescription('Apocare');
        $drawing->setPath($path);
        $drawing->setHeight(48);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(4);
        $drawing->setOffsetY(2);

        return [$drawing];
    }

    protected function kopLogoPath(): ?string
    {
        $path = public_path('assets/brand/apocare-logo.png');
        return is_string($path) ? $path : null;
    }

    protected function kopLastColumn(): string
    {
        if (property_exists($this, 'kopLastColumn') && is_string($this->kopLastColumn) && $this->kopLastColumn !== '') {
            return $this->kopLastColumn;
        }

        return 'G';
    }
}
