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
use App\Models\Transactions\ContractInfo;
use DB;
use Carbon\Carbon;

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
    
    public function expiringContractsList()
    {
        $from = Carbon::now();
        $to = Carbon::now()->addMonths(4);
        $expiring_contracts = ContractInfo::leftJoin('b_refdepartments', 'b_refdepartments.department_id', '=', 'b_contract_info.department_id')
                            ->leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_contract_info.tenant_id')
                            ->leftJoin('b_reflocations', 'b_reflocations.location_id', '=', 'b_contract_info.location_id')
                            ->leftJoin('b_refcategory', 'b_refcategory.category_id', '=', 'b_contract_info.category_id')
                            ->leftJoin('b_refnatureofbusiness', 'b_refnatureofbusiness.nature_of_business_id', '=', 'b_contract_info.nature_of_business_id')
                            ->whereDate('termination_date', '<=', $from)
                            ->orWhereBetween('termination_date', [$from, $to])
                            ->where('b_contract_info.is_deleted', 0)
                            ->get();
        return Reference::collection($expiring_contracts);
    }

    public function newContractsList()
    {
        $new_contracts = ContractInfo::leftJoin('b_refdepartments', 'b_refdepartments.department_id', '=', 'b_contract_info.department_id')
                            ->leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_contract_info.tenant_id')
                            ->leftJoin('b_reflocations', 'b_reflocations.location_id', '=', 'b_contract_info.location_id')
                            ->leftJoin('b_refcategory', 'b_refcategory.category_id', '=', 'b_contract_info.category_id')
                            ->leftJoin('b_refnatureofbusiness', 'b_refnatureofbusiness.nature_of_business_id', '=', 'b_contract_info.nature_of_business_id')
                            ->where('b_contract_info.is_deleted', 0)
                            ->where('is_renewal', 0)
                            ->where('renewed', 0)
                            ->get();
        return Reference::collection($new_contracts);
    }
}
