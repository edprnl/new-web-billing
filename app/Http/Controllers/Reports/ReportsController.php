<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Exports;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
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
        // return Excel::download(new Exports($location_id, $app_year, $app_month, $charge_type), 'invoices.xlsx');
        return Excel::download(new Exports, 'users.xlsx');
    }
}
