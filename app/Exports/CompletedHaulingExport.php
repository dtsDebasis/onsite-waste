<?php

namespace App\Exports;
  
use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class CompletedHaulingExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
        $list = app('App\Models\CompanyHauling')->getListing($this->_srchparams);
        $data = [];
        foreach($list as $key=>$hval){
            $field = [];
            $field['branch_name'] = (isset($hval->branch_details) && $hval->branch_details)?$hval->branch_details->name:'NA';
            $field['location'] = (isset($hval->branch_details->addressdata) && $hval->branch_details->addressdata)?$hval->branch_details->addressdata->addressline1:'NA';
            $field['driver_name'] = ($hval->driver_name)?$hval->driver_name:'NA';
            $field['number_of_boxes'] = ($hval->number_of_boxes)?$hval->number_of_boxes.' Box':'NA';
            $field['package'] = (isset($hval->package_details) && $hval->package_details)?$hval->package_details->name:'NA';
            $field['date'] = ($hval->date)?\App\Helpers\Helper::dateConvert($hval->date):'NA';
            $field['status'] = 'Completed';
            $data[] = $field;
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            'Branch Name',
            'Location',
            'Driver Name',
            'Number Of Boxes',
            'Package',
            'Date',
            'Status'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:G1'; // All headers
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