<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Resources\Reference;
use App\Models\References\Location;
use App\Models\References\Months;
use DB;

class ReportsController extends Controller
{
    public function tenantsPerSquareMeter($location_id)
    {
        return DB::select('CALL tenants_per_sqm_rate('.$location_id.')');
    }

    public function contractsMasterList($order_by, $order_type)
    {
        return DB::select('CALL contracts_master_list("'.$order_by.'","'.$order_type.'")');
    }

    public function rentalAndCharges($location_id, $app_year, $app_month, $charge_type)
    {
        $data =  DB::select('CALL rental_and_charges("'.$location_id.'","'.$app_year.'","'.$app_month.'","'.$charge_type.'")');

        if(count($data) > 0){        
            $month = Months::findOrFail($app_month);
            $month_name = $month->month_name;

            $location_desc = 'All';
            if($location_id != 0){
                $location = Location::findOrFail($location_id);
                $location_desc = $location->location_desc;
            }

            if($charge_type == 0)
            {
                $text = 'All';
            }

            else if($charge_type == 1)
            {
                $text = 'Utility';
            }

            else if($charge_type == 2)
            {
                $text = 'Miscellaneous';
            }

            else
            {
                $text = 'Other';
            }

            $data = json_decode( json_encode($data), true);

            foreach($data[0] as $key => $value)
            {
                $mykey[] = $key;
            }

            $inputFileName = 'uploads/rentalandcharges.xlsx';
            
            $spreadsheet =  PhpSpreadsheet\IOFactory::load($inputFileName);
            $spreadsheet->getActiveSheet()->mergeCells('A1:J1');
            $spreadsheet->getActiveSheet()->mergeCells('A2:J2');
            $spreadsheet->getActiveSheet()->mergeCells('A3:J3');
            $spreadsheet->getActiveSheet()->setCellValue(
                'A1',
                'Rental Rates and '.$text.' Charges'
            );
            $spreadsheet->getActiveSheet()->setCellValue(
                'A2',
                'Location : '.$location_desc
            );
            $spreadsheet->getActiveSheet()->setCellValue(
                'A3',
                'Applicable Date : '.$month_name.' '.$app_year 
            );
            $spreadsheet->getActiveSheet()
            ->fromArray(
                $mykey,  
                NULL,
                'A5'         
            );
            $spreadsheet->getActiveSheet()
            ->fromArray(
                $data, 
                NULL,    
                'A6'        
            ); 

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="data.xlsx"'); /*-- $filename is  xsl filename ---*/
            header('Cache-Control: max-age=0');
    
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
    }
}
