<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Models\Transactions\PaymentInfo;
use App\Models\References\Tenants;
use App\Models\Transactions\ContractInfo;
use App\Http\Controllers\Controller;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($payment_type)
    {
        $payment_line = DB::select(
                                "SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 1
                                UNION ALL 
                                SELECT
                                    IFNULL(SUM(amount_paid), 0) as amount
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 2
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 3
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 4
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 5
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 6
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 7
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 8
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 9
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 10
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 11
                                UNION ALL 
                                SELECT 
                                    IFNULL(SUM(amount_paid), 0) as amount_paid
                                FROM b_payment_info 
                                WHERE MONTH(payment_date) = 12"
        );

        $tenants = Tenants::select(DB::raw('count(*) as no_of_tenants'))
                            ->where('is_deleted', 0)
                            ->get();

        $contracts = ContractInfo::select(DB::raw('count(*) as no_of_contracts'))
                            ->where('is_deleted', 0)
                            ->get();
        $dashboard['payment_line'] = $month;
        $dashboard['tenants'] = Reference::collection($tenants);
        $dashboard['contracts'] = Reference::collection($contracts);

        return $month;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
