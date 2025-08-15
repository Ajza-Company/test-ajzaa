<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromView, Responsable, WithColumnWidths, WithStyles
{
    use Exportable;

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsible.
     */
    private string $fileName = 'leads.xlsx';

    /**
     * Optional Writer Type
     */
    private string $writerType = Excel::XLSX;

    /**
     * Optional headers
     */
    private array $headers = [
        // 'Content-Type' => 'text/csv',
    ];

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 25,
            'D' => 25,
            'F' => 10,
            'G' => 15,
        ];
    }

    /**
     * Create a new instance.
     *
     * @param $orders
     */
    public function __construct(private $orders)
    {

    }

    public function view(): View
    {
        return view('exports.orders', [
            'orders' => $this->orders
        ]);
    }
}
