<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tenants;
use App\Http\Resources\Reference as TenantResource;
use Carbon\Carbon;
use Auth;

class TenantsController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth');
    // }
    
    public function index()
    {
        $tenants = Tenants::where('is_deleted', 0)->orderBy('tenant_id', 'desc')->get();
        return TenantResource::collection($tenants);
    }

    public function create(Request $request)
    {  
        $tenant = new Tenant;
        $tenant->trade_name = $request->input('trade_name');
        $tenant->company_name = $request->input('company_name');
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
        
        if($tenant->save()){
            $tenant_id = $tenant->tenant_id;
            $mtenant = Tenant::findOrFail($tenant_id);
            $mtenant->tenant_code = 'T-'.str_pad($tenant_id,7,"0",STR_PAD_LEFT);
            if($mtenant->save()){
                $response['success'] = "Success";
                $response['message'] = "Success";
                $response['row'] = $mtenant;
                return $response;
            }
        }
    }

    public function show($id)
    {
        $tenant = Tenant::findOrFail($id);
        return new TenantResource($tenant);
    }

    public function update(Request $request)
    {
        $tenant = Tenant::findOrFail($request->input('tenant_id'));
        $tenant->trade_name = $request->input('trade_name');
        $tenant->company_name = $request->input('company_name');
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
        
        if($tenant->save()){
            $response['success'] = "Success";
            $response['message'] = "Success";
            $response['row'] = $tenant;
            return $response;
        }
    }

    public function delete($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->is_deleted = 1;
        $tenant->deleted_datetime = Carbon::now();
        $tenant->deleted_by = Auth::user()->id;
        
        if($tenant->save()){
            $response['success'] = "Success";
            $response['message'] = "Success";
            return $response;
        }
    }
}
