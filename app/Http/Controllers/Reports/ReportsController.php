<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
