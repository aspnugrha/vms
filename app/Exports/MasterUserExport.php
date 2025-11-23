<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterUserExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(public $request)
    {
    }

    public function array(): array
    {
        $active = $this->request->filter_active;
        $created_at = $this->request->filter_created_at;

        $data = User::orderBy('created_at', 'DESC')
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
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No', 'Name', 'Email', 'Phone Number', 'Active'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Center header
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');

        $lastRow = count($this->array()) + 1;

        // Center kolom
        $sheet->getStyle("A2:A{$lastRow}")
            ->getAlignment()->setHorizontal('center');
        $sheet->getStyle("E2:E{$lastRow}")
            ->getAlignment()->setHorizontal('center');

        // Border all cells
        $sheet->getStyle("A1:E{$lastRow}")
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
        ];
    }
}
