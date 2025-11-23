<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterVisitorExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(public $request)
    {
    }

    public function array(): array
    {
        $active = $this->request->filter_active;
        $created_at = $this->request->filter_created_at;

        $data = Visitor::orderBy('created_at', 'DESC')
            ->when($active != null, function ($query) use($active){
                $query->where('active', $active);
            })
            ->when($created_at != null, function ($query) use($created_at){
                $created_ranges = explode(' - ', $created_at);
                $query->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($created_ranges[0].' 00:00:00')));
                $query->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($created_ranges[1].' 23:59:59')));
            })->get();

        // $rows[] = ['No', 'Name', 'Email', 'Phone Number', 'Active']; // header

        foreach ($data as $index => $item) {
            $rows[] = [
                ($index + 1),
                $item->name,
                $item->email,
                $item->phone_number,
                ($item->active) ? 'Active' : 'Not Active',
                (int)$item->total_checkin,
                (int)$item->total_checkout,
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No', 'Name', 'Email', 'Phone Number', 'Active', 'Total Checkin', 'Total Checkout'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // Center header
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal('center');

        $lastRow = count($this->array()) + 1;

        // Center kolom
        $sheet->getStyle("A2:A{$lastRow}")
            ->getAlignment()->setHorizontal('center');
        $sheet->getStyle("G2:G{$lastRow}")
            ->getAlignment()->setHorizontal('center');

        // Border all cells
        $sheet->getStyle("A1:G{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle('thin');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 30,
            'C' => 35,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
        ];
    }
}
