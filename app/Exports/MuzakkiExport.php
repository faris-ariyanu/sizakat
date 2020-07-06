<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MuzakkiExport implements FromView, ShouldAutoSize, WithEvents
{
    public $Query;
    public $Total;

    public function __construct($Query,$Total)
    {
        $this->Query = $Query;
        $this->Total = $Total;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                // $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(15);
                // $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setBold(true);
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000'],
                        ]
                    ]
                ];

                $heading_cell = ["A1","B1","C1","D1","E1","F1","G1","H1","I1","J1"];
                foreach($heading_cell as $cell){
                    $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($styleArray);
                }

                foreach(array("A","B","C","D","E","F","G","H","I","J") as $cell){
                    for($i = 0; $i < $this->Total + 9; $i++){
                        $event->sheet->getDelegate()->getStyle($cell.$i)->applyFromArray($styleArray);
                        $event->sheet->getDelegate()->getStyle($cell.$i)->getFont()->setSize(14);
                    }
                }
            },
        ];
    }

    public function view(): View
    {
        return view('exports.muzakki', [
            'muzakki' => $this->Query
        ]);
    }
}