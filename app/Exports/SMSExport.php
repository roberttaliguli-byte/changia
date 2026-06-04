<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SMSExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $exportData = [];
        foreach ($this->data as $index => $item) {
            $exportData[] = [
                'S/N' => $index + 1,
                'Jina' => $item['name'] ?? '',
                'Namba ya Simu' => $item['phone'],
                'Ujumbe' => $item['message'],
                'Hali' => $item['status'],
                'Tarehe' => now()->format('d/m/Y H:i:s')
            ];
        }
        return $exportData;
    }

    public function headings(): array
    {
        return [
            'S/N',
            'Jina la Mpokeaji',
            'Namba ya Simu',
            'Ujumbe uliotumwa',
            'Hali',
            'Tarehe ya Kutuma'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}