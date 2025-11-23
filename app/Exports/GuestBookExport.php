<?php

namespace App\Exports;

use App\Models\GuestBook;
use App\Models\User;
use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuestBookExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(public $request)
    {
    }

    public function array(): array
    {
        $type = $this->request->filter_type;
        $visitor = $this->request->filter_visitor;
        $created_at = $this->request->filter_created_at;
        $checkin = $this->request->filter_checkin;
        $checkout = $this->request->filter_checkout;

        $data = GuestBook::orderBy('created_at', 'DESC')
                ->when($type != null, function ($query) use($type) {
                    $query->where('type', $type);
                })
                ->when($visitor != null, function ($query) use($visitor) {
                    $query->whereIn('visitor_id', $visitor);
                })
                ->when($created_at != null, function ($query) use($created_at) {
                    $created_ranges = explode(' - ', $created_at);
                    $query->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($created_ranges[0].' 00:00:00')));
                    $query->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($created_ranges[1].' 23:59:59')));
                })
                ->when($checkin != null, function ($query) use($checkin) {
                    $checkin_ranges = explode(' - ', $checkin);
                    $query->where('checkin_time', '>=', date('Y-m-d H:i:s', strtotime($checkin_ranges[0].' 00:00:00')));
                    $query->where('checkin_time', '<=', date('Y-m-d H:i:s', strtotime($checkin_ranges[1].' 23:59:59')));
                })
                ->when($checkout != null, function ($query) use($checkout) {
                    $checkout_ranges = explode(' - ', $checkout);
                    $query->where('checkout_time', '>=', date('Y-m-d H:i:s', strtotime($checkout_ranges[0].' 00:00:00')));
                    $query->where('checkout_time', '<=', date('Y-m-d H:i:s', strtotime($checkout_ranges[1].' 23:59:59')));
                })
                ->get();

        foreach ($data as $index => $item) {
            $rows[] = [
                ($index + 1),
                $item->visitor_name,
                $item->visitor_email,
                $item->visitor_phone_number,
                $item->visit_type,
                ($item->visit_type == 'IN' ? date('d-m-Y H:i:s', strtotime($item->checkin_time)) : ''),
                ($item->visit_type == 'OUT' ? date('d-m-Y H:i:s', strtotime($item->checkout_time)) : ''),
                ($item->visit_type == 'OUT' ? $item->visit_time_total.' detik' : ''),
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No', 'Visitor Name', 'Visitor Email', 'Visitor Phone Number', 'Type', 'Checkin', 'Checkout', 'Visit Time Total'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Center header
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');

        $lastRow = count($this->array()) + 1;

        // Center kolom
        $sheet->getStyle("A2:A{$lastRow}")
            ->getAlignment()->setHorizontal('center');
        $sheet->getStyle("G2:G{$lastRow}")
            ->getAlignment()->setHorizontal('center');
        $sheet->getStyle("E2:E{$lastRow}")
            ->getAlignment()->setHorizontal('center');

        // Border all cells
        $sheet->getStyle("A1:H{$lastRow}")
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
            'H' => 20,
        ];
    }
}
