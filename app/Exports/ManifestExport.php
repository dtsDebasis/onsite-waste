<?php

namespace App\Exports;
  
use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class ManifestExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;
    private $_srchparams;
    public function __construct($data){
        $this->_srchparams = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $list = app('App\Models\Manifest')->getListing($this->_srchparams);
        $data = [];
        foreach($list as $key=>$hval){
            $field = [];
            $field['uniq_id'] = ($hval->uniq_id)?'#'.$hval->uniq_id:'NA';            
            $field['date'] = ($hval->date)?\App\Helpers\Helper::dateConvert($hval->date):'NA';
            $field['location'] = ($hval->branch_address)?$hval->branch_address:'NA';
            $field['status'] = ($hval->status == 1)?'Confirmed':'Not Confirm';
            $data[] = $field;
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            'Manifests No',
            'Date',
            'Location',            
            'Status'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers
                //$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
           	$event->sheet->getStyle($cellRange)->getFill()
			          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			          ->getStartColor()->setARGB('F84300');
    			
				$styleArray = [
					
					'font' => [
						'name'      =>  'Calibri',
						'size'      =>  12,
						'bold'      =>  true,
						'color' => ['argb' => 'FFFFFF'],
					],
					
					'alignment' => [

                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    
                    
					
				];
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}