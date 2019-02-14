<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\References\Tenants;
use App\Models\Transactions\ContractInfo;
use App\Models\Transactions\BillingInfo;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;

class TenantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tenants = Tenants::where('is_deleted', 0)->orderBy('tenant_id', 'desc')->get();
        return Reference::collection($tenants);
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
                'trade_name' => 'required',
                'company_name' => 'required',
                'space_code' => 'required',
                'business_concept' => 'required',
                'head_office_address' => 'required',
                'billing_address' => 'required',
                'contact_person' => 'required',
                'designation' => 'required',
                'contact_number' => 'required',
                'tin_number' => 'required',
            ]
        )->validate();
        
        $tenant = new Tenants;
        $tenant->tenant_code = DB::select("select CreateTenantCode() as tenant_code")[0]->tenant_code;
        $tenant->trade_name = $request->input('trade_name');
        $tenant->company_name = $request->input('company_name');
        $tenant->space_code = $request->input('space_code');
        $tenant->business_concept = $request->input('business_concept');
        $tenant->head_office_address = $request->input('head_office_address');
        $tenant->billing_address = $request->input('billing_address');
        $tenant->contact_person = $request->input('contact_person');
        $tenant->designation = $request->input('designation');
        $tenant->contact_number = $request->input('contact_number');
        $tenant->email_address = $request->input('email_address');
        $tenant->tin_number = $request->input('tin_number');
        $tenant->is_auto = $request->input('is_auto');
        $tenant->business_permit = $request->input('business_permit');
        $tenant->tenant_information_sheet = $request->input('tenant_information_sheet');
        $tenant->valid_id = $request->input('valid_id');
        $tenant->tin_cor = $request->input('tin_cor');
        $tenant->dti_sec = $request->input('dti_sec');
        $tenant->notarized_contract = $request->input('notarized_contract');
        $tenant->proof_of_billing = $request->input('proof_of_billing');
        $tenant->others = $request->input('others');
        $tenant->others_specify = $request->input('others_specify');
        $tenant->created_datetime = Carbon::now();
        $tenant->created_by = Auth::user()->id;

        // return json based from the resource data
        if($tenant->save()){
            return ( new Reference( $tenant ))
                ->response()
                ->setStatusCode(201);
        }
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
        $tenant = Tenants::findOrFail($id);

        return ( new Reference( $tenant ) )
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
                'trade_name' => 'required',
                'company_name' => 'required',
                'space_code' => 'required',
                'business_concept' => 'required',
                'head_office_address' => 'required',
                'billing_address' => 'required',
                'contact_person' => 'required',
                'designation' => 'required',
                'contact_number' => 'required',
                'tin_number' => 'required',
            ]
        )->validate();

        $tenant = Tenants::findOrFail($id);
        $tenant->trade_name = $request->input('trade_name');
        $tenant->company_name = $request->input('company_name');
        $tenant->space_code = $request->input('space_code');
        $tenant->business_concept = $request->input('business_concept');
        $tenant->head_office_address = $request->input('head_office_address');
        $tenant->billing_address = $request->input('billing_address');
        $tenant->contact_person = $request->input('contact_person');
        $tenant->designation = $request->input('designation');
        $tenant->contact_number = $request->input('contact_number');
        $tenant->email_address = $request->input('email_address');
        $tenant->tin_number = $request->input('tin_number');
        $tenant->is_auto = $request->input('is_auto');
        $tenant->business_permit = $request->input('business_permit');
        $tenant->tenant_information_sheet = $request->input('tenant_information_sheet');
        $tenant->valid_id = $request->input('valid_id');
        $tenant->tin_cor = $request->input('tin_cor');
        $tenant->dti_sec = $request->input('dti_sec');
        $tenant->notarized_contract = $request->input('notarized_contract');
        $tenant->proof_of_billing = $request->input('proof_of_billing');
        $tenant->others = $request->input('others');
        $tenant->others_specify = $request->input('others_specify');
        $tenant->modified_datetime = Carbon::now();
        $tenant->modified_by = Auth::user()->id;

        $tenant->save();

        //return json based from the resource data
        return ( new Reference( $tenant ))
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
        $tenant = Tenants::findOrFail($id);
        $tenant->is_deleted = 1;
        $tenant->deleted_datetime = Carbon::now();
        $tenant->deleted_by = Auth::user()->id;

        //update classification based on the http json body that is sent
        $tenant->save();

        return ( new Reference( $tenant ) )
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

    public function tenantHistory($id)
    {
        return DB::select('CALL tenant_history('.$id.')');
    }

    public function checkIfUsed($id)
    {
        $exists = 'false';

        if(ContractInfo::where('tenant_id', '=', $id)
            ->where('is_deleted', 0)
            ->exists()) {
            $exists = 'true';
        }
        
        if(BillingInfo::where('tenant_id', '=', $id)
            ->where('is_deleted', 0)
            ->exists()) {
            $exists = 'true';
        }

        return $exists;
    }
}
