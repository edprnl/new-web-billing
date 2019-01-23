<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\References\BillingPeriod;
use App\Models\Transactions\BillingInfo;
use App\Models\Transactions\BillingSchedule;
use App\Models\Transactions\BillingMiscCharges;
use App\Models\Transactions\BillingOthrCharges;
use App\Models\Transactions\BillingUtilCharges;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

class BillingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($period_id = null, $department_id = null)
    {
        $billings = BillingInfo::leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_billing_info.tenant_id')
                            ->leftJoin('b_contract_info', 'b_contract_info.contract_id', '=', 'b_billing_info.contract_id')
                            ->leftJoin('b_refmonths', 'b_refmonths.month_id', '=', 'b_billing_info.month_id')
                            ->where('b_billing_info.is_deleted', 0);     
        if($period_id != null){
            $billings->where('b_billing_info.period_id', $period_id);
        }
        if($department_id != null && $department_id != 0){
            $billings->where('b_contract_info.department_id', $department_id);
        }
        return Reference::collection($billings->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Validator::make($request->all(),
            [
                'tenant_id' => 'required|not_in:0',
                'contract_id' => 'required|not_in:0',
            ],  ['not_in' => 'The :attribute field is required.']
        )->setAttributeNames([
            'tenant_id' => 'tenant',
            'contract_id' => 'contract']
        )->validate();

        $billing_info = new BillingInfo;
        $billing_util_charges = new BillingUtilCharges;
        $billing_misc_charges = new BillingMiscCharges;
        $billing_othr_charges = new BillingOthrCharges;

        $billing_info->billing_no = DB::raw("CreateBillingNo()");
        $billing_info->period_id = $request->input('period_id');
        $billing_info->tenant_id = $request->input('tenant_id');
        $billing_info->contract_id = $request->input('contract_id');
        $billing_info->month_id = $request->input('month_id');
        $billing_info->app_year = $request->input('app_year');
        $billing_info->due_date = date("Y-m-d", strtotime($request->input('due_date')));
        $billing_info->total_fixed_rent = $request->input('total_fixed_rent');
        $billing_info->total_util_charges = $request->input('total_util_charges');
        $billing_info->total_misc_charges = $request->input('total_misc_charges');
        $billing_info->total_othr_charges = $request->input('total_othr_charges');
        $billing_info->sub_total = $request->input('sub_total');
        $billing_info->vatable_amount = $request->input('vatable_amount');
        $billing_info->discounted_vatable_amount = $request->input('discounted_vatable_amount');
        $billing_info->vat_percent = $request->input('vat_percent');
        $billing_info->total_vat = $request->input('total_vat');
        $billing_info->discounted_total_amount_due = $request->input('discounted_total_amount_due');
        $billing_info->total_amount_due = $request->input('total_amount_due');
        $billing_info->total_adjusted_in = $request->input('total_adjusted_in');
        $billing_info->total_adjusted_out = $request->input('total_adjusted_out');
        $billing_info->wtax_amount = $request->input('wtax_amount');
        $billing_info->wtax_percent = $request->input('wtax_percent');

        $billing_info->created_datetime = Carbon::now();
        $billing_info->created_by = Auth::user()->id;

        if($billing_info->save()){
            $billing_id = $billing_info->billing_id;
            $schedules_dataSet = [];
            $utilities_dataSet = [];
            $miscs_dataSet = [];
            $others_dataSet = [];
            
            $schedules = $request->input('schedules');
            foreach($schedules as $schedule){
                $schedules_dataSet[] = [
                    'billing_id' => $billing_id,
                    'month_id' => $schedule['month_id'],
                    'app_year' => $schedule['app_year'],
                    'discounted_line_total' => $schedule['discounted_amount_due'],
                    'line_total' => $schedule['amount_due'],
                    'is_vatted' => $schedule['is_vatted'],
                    'billing_schedule_notes' => $schedule['contract_schedule_notes']
                ];
            }

            $utilities = $request->input('utilities');
            foreach($utilities as $utility){
                $utilities_dataSet[] = [
                    'billing_id' => $billing_id,
                    'charge_id' => $utility['charge_id'],
                    'billing_util_rate' => $utility['contract_rate'],
                    'billing_util_reading' => $utility['contract_default_reading'],
                    'billing_util_line_total' => $utility['contract_line_total'],
                    'billing_util_is_vatted' => $utility['contract_is_vatted'],
                    'billing_util_notes' => $utility['contract_notes']
                ];
            }

            $miscs = $request->input('miscellaneous');
            foreach($miscs as $misc){
                $miscs_dataSet[] = [
                    'billing_id' => $billing_id,
                    'charge_id' => $misc['charge_id'],
                    'billing_misc_rate' => $misc['contract_rate'],
                    'billing_misc_reading' => $misc['contract_default_reading'],
                    'billing_misc_line_total' => $misc['contract_line_total'],
                    'billing_misc_is_vatted' => $misc['contract_is_vatted'],
                    'billing_misc_notes' => $misc['contract_notes']
                ];
            }

            $others = $request->input('other');
            foreach($others as $other){
                $others_dataSet[] = [
                    'billing_id' => $billing_id,
                    'charge_id' => $other['charge_id'],
                    'billing_othr_rate' => $other['contract_rate'],
                    'billing_othr_reading' => $other['contract_default_reading'],
                    'billing_othr_line_total' => $other['contract_line_total'],
                    'billing_othr_is_vatted' => $other['contract_is_vatted'],
                    'billing_othr_notes' => $other['contract_notes']
                ];
            }

            DB::table('b_billing_schedule')->insert($schedules_dataSet);
            DB::table('b_billing_util_charges')->insert($utilities_dataSet);
            DB::table('b_billing_misc_charges')->insert($miscs_dataSet);
            DB::table('b_billing_othr_charges')->insert($others_dataSet);
        }
        $data = BillingInfo::leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_billing_info.tenant_id')
                            ->leftJoin('b_contract_info', 'b_contract_info.contract_id', '=', 'b_billing_info.contract_id')
                            ->leftJoin('b_refmonths', 'b_refmonths.month_id', '=', 'b_billing_info.month_id')
                            ->findOrFail($billing_info->billing_id);
        return ( new Reference( $data ) )
                ->response()
                ->setStatusCode(201);
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

    public function scheduleAndCharges($id)
    {
        DB::statement(DB::raw('set @row=0'));
        $schedules = BillingSchedule::select(
                        'b_billing_schedule.*',
                        'b_billing_schedule.billing_schedule_notes as contract_schedule_notes',
                        'discounted_line_total as discounted_amount_due',
                        'line_total as amount_due',
                        'b_refmonths.*',
                        DB::raw("@row := @row + 1 as count")
                    )
                    ->join('b_refmonths', 'b_refmonths.month_id', '=', 'b_billing_schedule.month_id')
                    ->where('billing_id', $id)->get();
        $util_charges = BillingUtilCharges::select(
                        'billing_util_id',
                        'billing_id',
                        'billing_util_rate as contract_rate',
                        'billing_util_reading as contract_default_reading',
                        'billing_util_is_vatted as contract_is_vatted',
                        'billing_util_notes as contract_notes',
                        'billing_util_line_total',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_billing_util_charges.charge_id')
                    ->where('billing_id', $id)->get();
        $misc_charges = BillingMiscCharges::select(
                        'billing_misc_id',
                        'billing_id',
                        'billing_misc_rate as contract_rate',
                        'billing_misc_reading as contract_default_reading',
                        'billing_misc_is_vatted as contract_is_vatted',
                        'billing_misc_notes as contract_notes',
                        'billing_misc_line_total',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_billing_misc_charges.charge_id')
                    ->where('billing_id', $id)->get();
        $othr_charges = BillingOthrCharges::select(
                        'billing_othr_id',
                        'billing_id',
                        'billing_othr_rate as contract_rate',
                        'billing_othr_reading as contract_default_reading',
                        'billing_othr_is_vatted as contract_is_vatted',
                        'billing_othr_notes as contract_notes',
                        'billing_othr_line_total',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_billing_othr_charges.charge_id')
                    ->where('billing_id', $id)->get();

        $billing['schedules'] = Reference::collection($schedules);
        $billing['util_charges'] = Reference::collection($util_charges);
        $billing['misc_charges'] = Reference::collection($misc_charges);
        $billing['othr_charges'] = Reference::collection($othr_charges);
        return $billing;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $soa = false)
    {
        $billing = BillingInfo::leftJoin('b_contract_info', 'b_contract_info.contract_id', '=', 'b_billing_info.contract_id')
                        ->leftJoin('b_refbillingperiod', 'b_refbillingperiod.period_id', '=', 'b_billing_info.period_id')
                        ->leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_contract_info.tenant_id');

        if($soa == true){
            $billing
                ->leftJoin('b_refmonths', 'b_refmonths.month_id', '=', 'b_refbillingperiod.month_id')
                ->leftJoin('b_refcategory', 'b_refcategory.category_id', '=', 'b_contract_info.category_id')
                ->leftJoin('b_reflocations', 'b_reflocations.location_id', '=', 'b_contract_info.location_id');
        }

        return ( new Reference( $billing->findOrFail($id) ) )
            ->response()
            ->setStatusCode(200);
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
        Validator::make($request->all(),
            [
                'tenant_id' => 'required|not_in:0',
                'contract_id' => 'required|not_in:0',
            ],  ['not_in' => 'The :attribute field is required.']
        )->setAttributeNames([
            'tenant_id' => 'tenant',
            'contract_id' => 'contract']
        )->validate();
        
        $billing_info = BillingInfo::findOrFail($request->input('billing_id'));
        $billing_util_charges = new BillingUtilCharges;
        $billing_misc_charges = new BillingMiscCharges;
        $billing_othr_charges = new BillingOthrCharges;

        $billing_info->period_id = $request->input('period_id');
        $billing_info->tenant_id = $request->input('tenant_id');
        $billing_info->contract_id = $request->input('contract_id');
        $billing_info->month_id = $request->input('month_id');
        $billing_info->app_year = $request->input('app_year');
        $billing_info->due_date = date("Y-m-d", strtotime($request->input('due_date')));
        $billing_info->total_discounted_rent = $request->input('total_discounted_rent');
        $billing_info->total_fixed_rent = $request->input('total_fixed_rent');
        $billing_info->total_util_charges = $request->input('total_util_charges');
        $billing_info->total_misc_charges = $request->input('total_misc_charges');
        $billing_info->total_othr_charges = $request->input('total_othr_charges');
        $billing_info->sub_total = $request->input('sub_total');
        $billing_info->vatable_amount = $request->input('vatable_amount');
        $billing_info->discounted_vatable_amount = $request->input('discounted_vatable_amount');
        $billing_info->vat_percent = $request->input('vat_percent');
        $billing_info->total_vat = $request->input('total_vat');
        $billing_info->total_amount_due = $request->input('total_amount_due');
        $billing_info->discounted_total_amount_due = $request->input('discounted_total_amount_due');
        $billing_info->total_adjusted_in = $request->input('total_adjusted_in');
        $billing_info->total_adjusted_out = $request->input('total_adjusted_out');
        $billing_info->wtax_amount = $request->input('wtax_amount');
        $billing_info->wtax_percent = $request->input('wtax_percent');

        $billing_info->modified_datetime = Carbon::now();
        $billing_info->modified_by = Auth::user()->id;

        if($billing_info->save()){
            $billing_id = $billing_info->billing_id;
            $schedules_dataSet = [];
            $utilities_dataSet = [];
            $miscs_dataSet = [];
            $others_dataSet = [];
            
            $old_schedules = BillingSchedule::where('billing_id', $billing_id);
            $old_schedules->delete();

            $old_utilities = BillingUtilCharges::where('billing_id', $billing_id);
            $old_utilities->delete();

            $old_miscs = BillingMiscCharges::where('billing_id', $billing_id);
            $old_miscs->delete();

            $old_other = BillingOthrCharges::where('billing_id', $billing_id);
            $old_other->delete();

            $schedules = $request->input('schedules');
            foreach($schedules as $schedule){
                $schedules_dataSet[] = [
                    'billing_id' => $billing_id,
                    'month_id' => $schedule['month_id'],
                    'app_year' => $schedule['app_year'],
                    'discounted_line_total' => $schedule['discounted_amount_due'],
                    'line_total' => $schedule['amount_due'],
                    'is_vatted' => $schedule['is_vatted'],
                    'billing_schedule_notes' => $schedule['contract_schedule_notes']
                ];
            }

            $utilities = $request->input('utilities');
            foreach($utilities as $utility){
                $utilities_dataSet[] = [
                    'billing_id' => $billing_id,
                    'charge_id' => $utility['charge_id'],
                    'billing_util_rate' => $utility['contract_rate'],
                    'billing_util_reading' => $utility['contract_default_reading'],
                    'billing_util_line_total' => $utility['contract_line_total'],
                    'billing_util_is_vatted' => $utility['contract_is_vatted'],
                    'billing_util_notes' => $utility['contract_notes']
                ];
            }

            $miscs = $request->input('miscellaneous');
            foreach($miscs as $misc){
                $miscs_dataSet[] = [
                    'billing_id' => $billing_id,
                    'charge_id' => $misc['charge_id'],
                    'billing_misc_rate' => $misc['contract_rate'],
                    'billing_misc_reading' => $misc['contract_default_reading'],
                    'billing_misc_line_total' => $misc['contract_line_total'],
                    'billing_misc_is_vatted' => $misc['contract_is_vatted'],
                    'billing_misc_notes' => $misc['contract_notes']
                ];
            }

            $others = $request->input('other');
            foreach($others as $other){
                $others_dataSet[] = [
                    'billing_id' => $billing_id,
                    'charge_id' => $other['charge_id'],
                    'billing_othr_rate' => $other['contract_rate'],
                    'billing_othr_reading' => $other['contract_default_reading'],
                    'billing_othr_line_total' => $other['contract_line_total'],
                    'billing_othr_is_vatted' => $other['contract_is_vatted'],
                    'billing_othr_notes' => $other['contract_notes']
                ];
            }

            DB::table('b_billing_schedule')->insert($schedules_dataSet);
            DB::table('b_billing_util_charges')->insert($utilities_dataSet);
            DB::table('b_billing_misc_charges')->insert($miscs_dataSet);
            DB::table('b_billing_othr_charges')->insert($others_dataSet);
        }
        $data = BillingInfo::leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_billing_info.tenant_id')
                            ->leftJoin('b_contract_info', 'b_contract_info.contract_id', '=', 'b_billing_info.contract_id')
                            ->leftJoin('b_refmonths', 'b_refmonths.month_id', '=', 'b_billing_info.month_id')
                            ->findOrFail($billing_info->billing_id);
        return ( new Reference( $data ) )
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage for deleting.
     * preventing force delete rather update the is_deleted = true
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $billing = BillingInfo::findOrFail($id);
        $billing->is_deleted = 1;
        $billing->deleted_datetime = Carbon::now();
        $billing->deleted_by = Auth::user()->id;

        //update classification based on the http json body that is sent
        $billing->save();

        return ( new Reference( $billing ) )
            ->response()
            ->setStatusCode(200);
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

    public function prevBalance($month_id, $app_year, $tenant_id){
        // $response['balance'] = DB::select("select GetPreviousBalance(".$month_id.", ".$app_year.", ".$tenant_id.")");
        return DB::select("select GetPreviousBalance(".$month_id.", ".$app_year.", ".$tenant_id.") as prevBalance");
    }

    public function asOfBalance($month_id, $app_year, $tenant_id){
        // $response['balance'] = DB::select("select GetPreviousBalance(".$month_id.", ".$app_year.", ".$tenant_id.")");
        return DB::select("select GetAsOfBalance(".$month_id.", ".$app_year.", ".$tenant_id.") as asOfBalance");
    }

    public function getBillingBalance($tenant_id){
        $billings = BillingInfo::select(
                                    'b_billing_info.billing_id',
                                    'b_billing_info.billing_no',
                                    'b_billing_info.app_year',
                                    'b_billing_info.month_id',
                                    'b_refmonths.month_name',
                                    DB::raw('b_billing_info.total_amount_due - (IFNULL(SUM(b_payment_details.amount_paid), 0) + IFNULL(SUM(b_payment_details.discount), 0)) as outstanding_balance'),
                                    DB::raw('b_billing_info.total_amount_due - b_billing_info.discounted_total_amount_due as bill_discount'),
                                    DB::raw('"0" as checker'),
                                    DB::raw('"0.00" as discount'),
                                    DB::raw('"0.00" as amount_paid')
                                )
                                // ->leftJoin('b_payment_details as pd', 'b_payment_details.billing_id', '=', 'b_billing_info.billing_id')
                                // ->leftJoin('b_payment_info', 'b_payment_info.payment_id', '=', 'b_payment_details.payment_id')
                                ->leftJoin('b_payment_details', function($join) {
                                    $join->on('b_payment_details.billing_id', '=', 'b_billing_info.billing_id')
                                        ->leftJoin('b_payment_info', 'b_payment_info.payment_id', '=', 'b_payment_details.payment_id')
                                        ->where('b_payment_info.is_canceled', 0)
                                        ;
                                  })
                                ->leftJoin('b_refmonths', 'b_refmonths.month_id', '=', 'b_billing_info.month_id')
                                ->where('b_billing_info.tenant_id', $tenant_id)
                                ->where('is_deleted', 0)
                                // ->where('b_payment_info.is_canceled', 0)
                                ->groupBy('billing_id')
                                ->havingRaw('outstanding_balance >= 0.9')
                                ->get();

        return ( new Reference( $billings) )
                                ->response()
                                ->setStatusCode(200);
    }

    public function checkIfUsed($id)
    {
        $exists = 'false';

        // if(BillingPeriod::leftJoin('b_billing_info', 'b_billing_info.period_id', '=', 'b_refbillingperiod.period_id')
        //     ->where('b_billing_info.billing_id', '=', $id)
        //     ->where('b_billing_info.is_deleted', 0)
        //     ->where('b_refbillingperiod.is_closed', 1)
        //     ->exists()) {
        //     $exists = 'true';
        // }
        
        return $exists;
    }
}
