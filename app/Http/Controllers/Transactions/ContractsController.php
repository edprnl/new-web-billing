<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transactions\BillingInfo;
use App\Models\Transactions\ContractInfo;
use App\Models\Transactions\ContractSchedule;
use App\Models\Transactions\ContractUtilCharges;
use App\Models\Transactions\ContractMiscCharges;
use App\Models\Transactions\ContractOthrCharges;
use App\Models\Transactions\ContractOtherFees;
use App\Models\References\FeeType;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tenant_id = null)
    {
        $contracts = ContractInfo::leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_contract_info.tenant_id')
                            ->leftJoin('b_refdepartments', 'b_refdepartments.department_id', '=', 'b_contract_info.department_id')
                            ->leftJoin('b_reflocations', 'b_reflocations.location_id', '=', 'b_contract_info.location_id')
                            ->leftJoin('b_refcontracttype', 'b_refcontracttype.contract_type_id', '=', 'b_contract_info.contract_type_id')
                            ->leftJoin('b_refcategory', 'b_refcategory.category_id', '=', 'b_contract_info.category_id')
                            ->leftJoin('b_refnatureofbusiness', 'b_refnatureofbusiness.nature_of_business_id', '=', 'b_contract_info.nature_of_business_id')
                            ->leftJoin(
                                DB::raw("
                                        (SELECT
                                            a.tenant_id,
                                            SUM(a.advance_rent) as c_advance_rent,
                                            SUM(a.security_deposit) as c_security_deposit,
                                            SUM(a.electric_meter_deposit) as c_electric_meter_deposit,
                                            SUM(a.water_meter_deposit) as c_water_meter_deposit,
                                            SUM(a.construction_deposit) as c_construction_deposit
                                        FROM
                                            (
                                                SELECT
                                                    tenant_id,
                                                    IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as advance_rent, 
                                                    0 as security_deposit,
                                                    0 as electric_meter_deposit,
                                                    0 as water_meter_deposit,
                                                    0 as construction_deposit
                                                FROM
                                                    b_contract_other_fees
                                                WHERE fee_type_id = 1
                                                GROUP BY tenant_id
                                                UNION ALL SELECT
                                                    tenant_id,
                                                    0 as advance_rent, 
                                                    IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as security_deposit,
                                                    0 as electric_meter_deposit,
                                                    0 as water_meter_deposit, 
                                                    0 as construction_deposit
                                                FROM
                                                    b_contract_other_fees
                                                WHERE fee_type_id = 2
                                                GROUP BY tenant_id
                                                UNION ALL SELECT
                                                    tenant_id,
                                                    0 as advance_rent, 
                                                    0 as security_deposit,
                                                    IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as electric_meter_deposit,
                                                    0 as water_meter_deposit, 
                                                    0 as construction_deposit
                                                FROM
                                                    b_contract_other_fees
                                                WHERE fee_type_id = 3
                                                GROUP BY tenant_id
                                                UNION ALL SELECT
                                                    tenant_id,
                                                    0 as advance_rent, 
                                                    0 as security_deposit,
                                                    0 as electric_meter_deposit,
                                                    IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as water_meter_deposit, 
                                                    0 as construction_deposit
                                                FROM
                                                    b_contract_other_fees
                                                WHERE fee_type_id = 4
                                                GROUP BY tenant_id
                                                UNION ALL SELECT
                                                    tenant_id,
                                                    0 as advance_rent, 
                                                    0 as security_deposit,
                                                    0 as electric_meter_deposit,
                                                    0 as water_meter_deposit, 
                                                    IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as construction_deposit
                                                FROM
                                                    b_contract_other_fees
                                                WHERE fee_type_id = 5
                                                GROUP BY tenant_id
                                            ) AS a
                                            GROUP BY tenant_id
                                ) a" ), 'b_contract_info.tenant_id', '=', 'a.tenant_id')
                            ->where('b_contract_info.is_deleted', 0);
        if($tenant_id != null){
            $contracts->where('b_contract_info.tenant_id', $tenant_id);
        }  

        return Reference::collection($contracts->orderBy('b_contract_info.contract_id','desc')->get());
    }

    public function getFeeType($tenant_id = null)
    {
        $fee_type = FeeType::select(
                                'b_reffeetype.*',
                                'cof.tenant_id',
                                DB::raw("IFNULL(SUM(cof.fee_credit), 0) - IFNULL(SUM(cof.fee_debit), 0) as amount"),
                                DB::raw('"" as history')
                            )
                            ->leftJoin('b_contract_other_fees as cof', function($join) use ($tenant_id){
                                $join->on('cof.fee_type_id', '=', 'b_reffeetype.fee_type_id')
                                    ->where('cof.tenant_id', $tenant_id)
                                    ;
                              })
                            // ->leftJoin('b_contract_other_fees as cof', 'cof.fee_type_id', '=', 'b_reffeetype.fee_type_id')
                            ->groupBy('fee_type_id')
                            ->get();

        return Reference::collection($fee_type);
    }

    public function saveFees(Request $request)
    {
        $fee = new ContractOtherFees;
        $fee->tenant_id = $request->input('tenant_id');
        $fee->fee_type_id = $request->input('fee_type_id');
        $fee->fee_credit = $request->input('amount');
        $fee->fee_notes = $request->input('fee_notes');
        $fee->created_datetime = Carbon::now();
        $fee->created_by = Auth::user()->id;
        $fee->save();

        return ( new Reference( $fee ) )
            ->response()
            ->setStatusCode(200);
    }

    public function contractFeesHistory($tenant_id, $fee_type_id)
    {
        DB::statement(DB::raw('set @balance=0'));
        $contract_fees = ContractOtherFees::select(
                                                'fee_debit',
                                                'fee_credit',
                                                'fee_notes',
                                                'created_datetime',
                                                DB::raw('@balance:= @balance + (fee_credit - fee_debit) as balance')
                                            )
                                            ->where('tenant_id', $tenant_id)
                                            ->where('fee_type_id', $fee_type_id)
                                            ->get();

        return Reference::collection($contract_fees);
    }

    public function scheduleAndCharges($id)
    {
        DB::statement(DB::raw('set @row=0'));
        $schedules = ContractSchedule::select(
                        'b_contract_schedule.*',
                        'b_refmonths.*',
                        DB::raw("@row := @row + 1 as count")
                    )
                    ->join('b_refmonths', 'b_refmonths.month_id', '=', 'b_contract_schedule.month_id')
                    ->where('contract_id', $id)->get();
        $util_charges = ContractUtilCharges::select(
                        'contract_util_id',
                        'contract_id',
                        'contract_util_rate as contract_rate',
                        'contract_util_default_reading as contract_default_reading',
                        'contract_util_is_vatted as contract_is_vatted',
                        'contract_util_notes as contract_notes',
                        'sort_key',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_contract_util_charges.charge_id')
                    ->where('contract_id', $id)->orderBy('sort_key', 'asc')->get();
        $misc_charges = ContractMiscCharges::select(
                        'contract_misc_id',
                        'contract_id',
                        'contract_misc_rate as contract_rate',
                        'contract_misc_default_reading as contract_default_reading',
                        'contract_misc_is_vatted as contract_is_vatted',
                        'contract_misc_notes as contract_notes',
                        'sort_key',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_contract_misc_charges.charge_id')
                    ->where('contract_id', $id)->orderBy('sort_key', 'asc')->get();
        $othr_charges = ContractOthrCharges::select(
                        'contract_othr_id',
                        'contract_id',
                        'contract_othr_rate as contract_rate',
                        'contract_othr_default_reading as contract_default_reading',
                        'contract_othr_is_vatted as contract_is_vatted',
                        'contract_othr_notes as contract_notes',
                        'sort_key',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_contract_othr_charges.charge_id')
                    ->where('contract_id', $id)->orderBy('sort_key', 'asc')->get();

        $contracts['schedules'] = Reference::collection($schedules);
        $contracts['util_charges'] = Reference::collection($util_charges);
        $contracts['misc_charges'] = Reference::collection($misc_charges);
        $contracts['othr_charges'] = Reference::collection($othr_charges);
        return $contracts;
    }

    public function scheduleAndChargesBilling($id, $app_year = NULL, $month_id = NULL)
    {
        DB::statement(DB::raw('set @row=0'));
        $schedules = ContractSchedule::select(
                        'b_contract_schedule.*',
                        'b_refmonths.*',
                        DB::raw("@row := @row + 1 as count")
                    )
                    ->join('b_refmonths', 'b_refmonths.month_id', '=', 'b_contract_schedule.month_id')
                    ->where('app_year', $app_year)
                    ->where('b_contract_schedule.month_id', $month_id)
                    ->where('contract_id', $id)
                    ->get();
        $util_charges = ContractUtilCharges::select(
                        'contract_util_id',
                        'contract_id',
                        'contract_util_rate as contract_rate',
                        'contract_util_default_reading as contract_default_reading',
                        'contract_util_is_vatted as contract_is_vatted',
                        'contract_util_notes as contract_notes',
                        'sort_key',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_contract_util_charges.charge_id')
                    ->where('contract_id', $id)->get();
        $misc_charges = ContractMiscCharges::select(
                        'contract_misc_id',
                        'contract_id',
                        'contract_misc_rate as contract_rate',
                        'contract_misc_default_reading as contract_default_reading',
                        'contract_misc_is_vatted as contract_is_vatted',
                        'contract_misc_notes as contract_notes',
                        'sort_key',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_contract_misc_charges.charge_id')
                    ->where('contract_id', $id)->get();
        $othr_charges = ContractOthrCharges::select(
                        'contract_othr_id',
                        'contract_id',
                        'contract_othr_rate as contract_rate',
                        'contract_othr_default_reading as contract_default_reading',
                        'contract_othr_is_vatted as contract_is_vatted',
                        'contract_othr_notes as contract_notes',
                        'sort_key',
                        'b_refcharges.charge_id',
                        'b_refcharges.charge_desc'
                    )
                    ->join('b_refcharges', 'b_refcharges.charge_id', '=', 'b_contract_othr_charges.charge_id')
                    ->where('contract_id', $id)->get();

        $contracts['schedules'] = Reference::collection($schedules);
        $contracts['util_charges'] = Reference::collection($util_charges);
        $contracts['misc_charges'] = Reference::collection($misc_charges);
        $contracts['othr_charges'] = Reference::collection($othr_charges);
        return $contracts;
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
                'department_id' => 'required|not_in:0',
                'nature_of_business_id' => 'required|not_in:0',
                'location_id' => 'required|not_in:0',
                'contract_type_id' => 'required|not_in:0',
                'category_id' => 'required|not_in:0',
                'contract_terms' => 'required|not_in:0|numeric',
                'commencement_date' => 'required',
                'termination_date' => 'required',
                'start_billing_date' => 'required',
                'contract_floor_area' => 'required|not_in:0|numeric',
                'contract_fixed_rent' => 'required|not_in:0|numeric',
                'schedules' => 'required'
            ],  ['not_in' => 'The :attribute field is required.']
        )->setAttributeNames([
            'tenant_id' => 'lessee/tenant',
            'department_id' => 'department',
            'nature_of_business_id' => 'nature of business',
            'location_id' => 'location',
            'contract_type_id' => 'contract type',
            'category_id' => 'category',
            'contract_floor_area' => 'app floor area',
            'contract_fixed_rent' => 'basic rental',
            'schedules' => 'schedule of payment']
        )->validate();

        $contract_info = new ContractInfo;
        $contract_schedule = new ContractSchedule;
        $contract_util_charges = new ContractUtilCharges;
        $contract_misc_charges = new ContractMiscCharges;
        $contract_othr_charges = new ContractOthrCharges;

        $contract_info->contract_no = DB::raw("CreateContractNo()");
        $contract_info->tenant_id = $request->input('tenant_id');
        $contract_info->contract_billing_address = $request->input('contract_billing_address');
        $contract_info->contract_type_id = $request->input('contract_type_id');
        $contract_info->category_id = $request->input('category_id');
        $contract_info->commencement_date = date("Y-m-d", strtotime($request->input('commencement_date')));
        $contract_info->termination_date = date('Y-m-d', strtotime($request->input('termination_date')));
        $contract_info->start_billing_date = date('Y-m-d', strtotime($request->input('start_billing_date')));
        $contract_info->location_id = $request->input('location_id');
        $contract_info->contract_signatory = $request->input('contract_signatory');
        $contract_info->contract_terms = $request->input('contract_terms');
        $contract_info->contract_approved_merch = $request->input('contract_approved_merch');
        $contract_info->contract_fixed_rent = $request->input('contract_fixed_rent');
        $contract_info->contract_discounted_rent = $request->input('contract_discounted_rent');
        $contract_info->contract_advance_rent = $request->input('contract_advance_rent');
        $contract_info->water_meter_deposit = $request->input('water_meter_deposit');
        $contract_info->construction_deposit = $request->input('construction_deposit');
        $contract_info->power_meter_deposit = $request->input('power_meter_deposit');
        $contract_info->escalation_notes = $request->input('escalation_notes');
        $contract_info->contract_remarks = $request->input('contract_remarks');
        $contract_info->security_deposit = $request->input('security_deposit');
        $contract_info->contract_escalation_percent = $request->input('contract_escalation_percent');
        $contract_info->contract_floor_area = $request->input('contract_floor_area');
        $contract_info->department_id = $request->input('department_id');
        $contract_info->nature_of_business_id = $request->input('nature_of_business_id');
        $contract_info->is_renewal = $request->input('is_renewal');

        $contract_info->created_datetime = Carbon::now();
        $contract_info->created_by = Auth::user()->id;
        
        if($contract_info->save()){
            $contract_id = $contract_info->contract_id;
            $schedules_dataSet = [];
            $utilities_dataSet = [];
            $miscs_dataSet = [];
            $others_dataSet = [];
            
            $schedules = $request->input('schedules');
            foreach($schedules as $schedule){
                $schedules_dataSet[] = [
                    'contract_id' => $contract_id,
                    'month_id' => $schedule['month_id'],
                    'app_year' => $schedule['app_year'],
                    'discounted_rent' => $schedule['discounted_rent'],
                    'fixed_rent' => $schedule['fixed_rent'],
                    'escalation_percent' => $schedule['escalation_percent'],
                    'escalation_amount' => $schedule['escalation_amount'],
                    'discounted_amount_due' => $schedule['discounted_amount_due'],
                    'amount_due' => $schedule['amount_due'],
                    'is_vatted' => $schedule['is_vatted'],
                    'contract_schedule_notes' => $schedule['contract_schedule_notes']
                ];
            }

            $utilities = $request->input('utilities');
            foreach($utilities as $utility){
                $utilities_dataSet[] = [
                    'contract_id' => $contract_id,
                    'charge_id' => $utility['charge_id'],
                    'contract_util_rate' => $utility['contract_rate'],
                    'contract_util_default_reading' => $utility['contract_default_reading'],
                    'contract_util_is_vatted' => $utility['contract_is_vatted'],
                    'contract_util_notes' => $utility['contract_notes'],
                    'sort_key' => $utility['sort_key']
                ];
            }

            $miscs = $request->input('miscellaneous');
            foreach($miscs as $misc){
                $miscs_dataSet[] = [
                    'contract_id' => $contract_id,
                    'charge_id' => $misc['charge_id'],
                    'contract_misc_rate' => $misc['contract_rate'],
                    'contract_misc_default_reading' => $misc['contract_default_reading'],
                    'contract_misc_is_vatted' => $misc['contract_is_vatted'],
                    'contract_misc_notes' => $misc['contract_notes'],
                    'sort_key' => $misc['sort_key']
                ];
            }

            $others = $request->input('other');
            foreach($others as $other){
                $others_dataSet[] = [
                    'contract_id' => $contract_id,
                    'charge_id' => $other['charge_id'],
                    'contract_othr_rate' => $other['contract_rate'],
                    'contract_othr_default_reading' => $other['contract_default_reading'],
                    'contract_othr_is_vatted' => $other['contract_is_vatted'],
                    'contract_othr_notes' => $other['contract_notes'],
                    'sort_key' => $other['sort_key']
                ];
            }

            DB::table('b_contract_schedule')->insert($schedules_dataSet);
            DB::table('b_contract_util_charges')->insert($utilities_dataSet);
            DB::table('b_contract_misc_charges')->insert($miscs_dataSet);
            DB::table('b_contract_othr_charges')->insert($others_dataSet);
        }

        $data = ContractInfo::leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_contract_info.tenant_id')
                            ->leftJoin('b_refdepartments', 'b_refdepartments.department_id', '=', 'b_contract_info.department_id')
                            ->leftJoin('b_reflocations', 'b_reflocations.location_id', '=', 'b_contract_info.location_id')
                            ->leftJoin('b_refcontracttype', 'b_refcontracttype.contract_type_id', '=', 'b_contract_info.contract_type_id')
                            ->leftJoin('b_refcategory', 'b_refcategory.category_id', '=', 'b_contract_info.category_id')
                            ->findOrFail($contract_info->contract_id);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contract = ContractInfo::join('b_tenants', 'b_tenants.tenant_id', '=', 'b_contract_info.tenant_id')
                                ->leftJoin(
                                    DB::raw("
                                            (SELECT
                                                a.tenant_id,
                                                SUM(a.advance_rent) as c_advance_rent,
                                                SUM(a.security_deposit) as c_security_deposit,
                                                SUM(a.electric_meter_deposit) as c_electric_meter_deposit,
                                                SUM(a.water_meter_deposit) as c_water_meter_deposit,
                                                SUM(a.construction_deposit) as c_construction_deposit
                                            FROM
                                                (
                                                    SELECT
                                                    tenant_id,
                                                        IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as advance_rent, 
                                                        0 as security_deposit,
                                                        0 as electric_meter_deposit,
                                                        0 as water_meter_deposit,
                                                        0 as construction_deposit
                                                    FROM
                                                        b_contract_other_fees
                                                    WHERE fee_type_id = 1
                                                    GROUP BY tenant_id
                                                    UNION ALL SELECT
                                                        tenant_id,
                                                        0 as advance_rent, 
                                                        IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as security_deposit,
                                                        0 as electric_meter_deposit,
                                                        0 as water_meter_deposit, 
                                                        0 as construction_deposit
                                                    FROM
                                                        b_contract_other_fees
                                                    WHERE fee_type_id = 2
                                                    GROUP BY tenant_id
                                                    UNION ALL SELECT
                                                        tenant_id,
                                                        0 as advance_rent, 
                                                        0 as security_deposit,
                                                        IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as electric_meter_deposit,
                                                        0 as water_meter_deposit, 
                                                        0 as construction_deposit
                                                    FROM
                                                        b_contract_other_fees
                                                    WHERE fee_type_id = 3
                                                    GROUP BY tenant_id
                                                    UNION ALL SELECT
                                                        tenant_id,
                                                        0 as advance_rent, 
                                                        0 as security_deposit,
                                                        0 as electric_meter_deposit,
                                                        IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as water_meter_deposit, 
                                                        0 as construction_deposit
                                                    FROM
                                                        b_contract_other_fees
                                                    WHERE fee_type_id = 4
                                                    GROUP BY tenant_id
                                                    UNION ALL SELECT
                                                        tenant_id,
                                                        0 as advance_rent, 
                                                        0 as security_deposit,
                                                        0 as electric_meter_deposit,
                                                        0 as water_meter_deposit, 
                                                        IFNULL(SUM(fee_credit), 0) - IFNULL(SUM(fee_debit), 0) as construction_deposit
                                                    FROM
                                                        b_contract_other_fees
                                                    WHERE fee_type_id = 5
                                                    GROUP BY tenant_id
                                                ) AS a
                                                GROUP BY tenant_id
                                    ) a" ), 'b_contract_info.tenant_id', '=', 'a.tenant_id')
                                ->findOrFail($id);

        return ( new Reference( $contract ) )
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
                'department_id' => 'required|not_in:0',
                'nature_of_business_id' => 'required|not_in:0',
                'location_id' => 'required|not_in:0',
                'contract_type_id' => 'required|not_in:0',
                'category_id' => 'required|not_in:0',
                'contract_terms' => 'required|not_in:0|numeric',
                'commencement_date' => 'required',
                'termination_date' => 'required',
                'start_billing_date' => 'required',
                'contract_floor_area' => 'required|not_in:0|numeric',
                'contract_fixed_rent' => 'required|not_in:0|numeric',
                'schedules' => 'required'
            ],  ['not_in' => 'The :attribute field is required.']
        )->setAttributeNames([
            'tenant_id' => 'lessee/tenant',
            'department_id' => 'department',
            'nature_of_business_id' => 'nature of business',
            'location_id' => 'location',
            'contract_type_id' => 'contract type',
            'category_id' => 'category',
            'contract_floor_area' => 'app floor area',
            'contract_fixed_rent' => 'basic rental',
            'schedules' => 'schedule of payment']
        )->validate();

        $contract_info = ContractInfo::findOrFail($request->input('contract_id'));
        $contract_schedule = new ContractSchedule;
        $contract_util_charges = new ContractUtilCharges;
        $contract_misc_charges = new ContractMiscCharges;
        $contract_othr_charges = new ContractOthrCharges;

        $contract_info->tenant_id = $request->input('tenant_id');
        $contract_info->contract_billing_address = $request->input('contract_billing_address');
        $contract_info->contract_type_id = $request->input('contract_type_id');
        $contract_info->category_id = $request->input('category_id');
        $contract_info->commencement_date = date("Y-m-d", strtotime($request->input('commencement_date')));
        $contract_info->termination_date = date('Y-m-d', strtotime($request->input('termination_date')));
        $contract_info->start_billing_date = date('Y-m-d', strtotime($request->input('start_billing_date')));
        $contract_info->location_id = $request->input('location_id');
        $contract_info->contract_signatory = $request->input('contract_signatory');
        $contract_info->contract_terms = $request->input('contract_terms');
        $contract_info->contract_approved_merch = $request->input('contract_approved_merch');
        $contract_info->contract_fixed_rent = $request->input('contract_fixed_rent');
        $contract_info->contract_discounted_rent = $request->input('contract_discounted_rent');
        $contract_info->contract_advance_rent = $request->input('contract_advance_rent');
        $contract_info->water_meter_deposit = $request->input('water_meter_deposit');
        $contract_info->construction_deposit = $request->input('construction_deposit');
        $contract_info->power_meter_deposit = $request->input('power_meter_deposit');
        $contract_info->escalation_notes = $request->input('escalation_notes');
        $contract_info->contract_remarks = $request->input('contract_remarks');
        $contract_info->security_deposit = $request->input('security_deposit');
        $contract_info->contract_escalation_percent = $request->input('contract_escalation_percent');
        $contract_info->contract_floor_area = $request->input('contract_floor_area');
        $contract_info->department_id = $request->input('department_id');
        $contract_info->nature_of_business_id = $request->input('nature_of_business_id');
        $contract_info->is_renewal = $request->input('is_renewal');

        $contract_info->created_datetime = Carbon::now();
        $contract_info->created_by = Auth::user()->id;
        
        if($contract_info->save()){
            $contract_id = $contract_info->contract_id;
            $schedules_dataSet = [];
            $utilities_dataSet = [];
            $miscs_dataSet = [];
            $others_dataSet = [];
            
            $old_schedules = ContractSchedule::where('contract_id', $contract_id);
            $old_schedules->delete();

            $old_utilities = ContractUtilCharges::where('contract_id', $contract_id);
            $old_utilities->delete();

            $old_miscs = ContractMiscCharges::where('contract_id', $contract_id);
            $old_miscs->delete();

            $old_other = ContractOthrCharges::where('contract_id', $contract_id);
            $old_other->delete();

            $schedules = $request->input('schedules');
            foreach($schedules as $schedule){
                $schedules_dataSet[] = [
                    'contract_id' => $contract_id,
                    'month_id' => $schedule['month_id'],
                    'app_year' => $schedule['app_year'],
                    'discounted_rent' => $schedule['discounted_rent'],
                    'fixed_rent' => $schedule['fixed_rent'],
                    'escalation_percent' => $schedule['escalation_percent'],
                    'escalation_amount' => $schedule['escalation_amount'],
                    'discounted_amount_due' => $schedule['discounted_amount_due'],
                    'amount_due' => $schedule['amount_due'],
                    'is_vatted' => $schedule['is_vatted'],
                    'contract_schedule_notes' => $schedule['contract_schedule_notes']
                ];
            }

            $utilities = $request->input('utilities');
            foreach($utilities as $utility){
                $utilities_dataSet[] = [
                    'contract_id' => $contract_id,
                    'charge_id' => $utility['charge_id'],
                    'contract_util_rate' => $utility['contract_rate'],
                    'contract_util_default_reading' => $utility['contract_default_reading'],
                    'contract_util_is_vatted' => $utility['contract_is_vatted'],
                    'contract_util_notes' => $utility['contract_notes'],
                    'sort_key' => $utility['sort_key']
                ];
            }

            $miscs = $request->input('miscellaneous');
            foreach($miscs as $misc){
                $miscs_dataSet[] = [
                    'contract_id' => $contract_id,
                    'charge_id' => $misc['charge_id'],
                    'contract_misc_rate' => $misc['contract_rate'],
                    'contract_misc_default_reading' => $misc['contract_default_reading'],
                    'contract_misc_is_vatted' => $misc['contract_is_vatted'],
                    'contract_misc_notes' => $misc['contract_notes'],
                    'sort_key' => $misc['sort_key']
                ];
            }

            $others = $request->input('other');
            foreach($others as $other){
                $others_dataSet[] = [
                    'contract_id' => $contract_id,
                    'charge_id' => $other['charge_id'],
                    'contract_othr_rate' => $other['contract_rate'],
                    'contract_othr_default_reading' => $other['contract_default_reading'],
                    'contract_othr_is_vatted' => $other['contract_is_vatted'],
                    'contract_othr_notes' => $other['contract_notes'],
                    'sort_key' => $other['sort_key']
                ];
            }

            DB::table('b_contract_schedule')->insert($schedules_dataSet);
            DB::table('b_contract_util_charges')->insert($utilities_dataSet);
            DB::table('b_contract_misc_charges')->insert($miscs_dataSet);
            DB::table('b_contract_othr_charges')->insert($others_dataSet);
        }

        $data = ContractInfo::leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_contract_info.tenant_id')
                            ->leftJoin('b_refdepartments', 'b_refdepartments.department_id', '=', 'b_contract_info.department_id')
                            ->leftJoin('b_reflocations', 'b_reflocations.location_id', '=', 'b_contract_info.location_id')
                            ->leftJoin('b_refcontracttype', 'b_refcontracttype.contract_type_id', '=', 'b_contract_info.contract_type_id')
                            ->leftJoin('b_refcategory', 'b_refcategory.category_id', '=', 'b_contract_info.category_id')
                            ->findOrFail($contract_info->contract_id);

        return ( new Reference($data) )
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
        $contract = ContractInfo::findOrFail($id);
        $contract->is_deleted = 1;
        $contract->deleted_datetime = Carbon::now();
        $contract->deleted_by = Auth::user()->id;

        //update classification based on the http json body that is sent
        $contract->save();

        return ( new Reference( $contract ) )
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

    public function checkIfUsed($id)
    {
        $exists = 'false';

        if(BillingInfo::where('contract_id', '=', $id)
            ->where('is_deleted', 0)
            ->exists()) {
            $exists = 'true';
        }
        
        return $exists;
    }
}
