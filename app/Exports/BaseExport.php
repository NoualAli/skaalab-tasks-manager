<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExport implements ShouldAutoSize, WithEvents, WithStyles
{
    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Apply border styles to the entire worksheet
        $cellRange = 'A1:' . $highestColumn . $highestRow;
        $sheet->getStyle($cellRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF333333'],
                ],
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return array(
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setAutoFilter('A1:' . $event->sheet->getDelegate()->getHighestColumn() . '1');
            }
        );
    }
}
