<?php

namespace App\Http\Controllers\Utilities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Utilities\CompanySettings;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;

class CompanySettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $company = CompanySettings::findOrFail($id);

        return ( new Reference( $company ) )
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
        $company = CompanySettings::findOrFail($id);
        
        $company->company_name = $request->input('company_name');
        $company->company_address = $request->input('company_address');
        $company->email_address = $request->input('email_address');
        $company->mobile_number = $request->input('mobile_number');
        $company->landline = $request->input('landline');
        $company->logo = $request->input('logo');
        $company->basic_rental_account_id = $request->input('basic_rental_account_id');
        $company->advance_rental_account_id = $request->input('advance_rental_account_id');
        $company->security_deposit_account_id = $request->input('security_deposit_account_id');
        $company->electric_meter_deposit_account_id = $request->input('electric_meter_deposit_account_id');
        $company->water_meter_deposit_account_id = $request->input('water_meter_deposit_account_id');
        $company->construction_deposit_account_id = $request->input('construction_deposit_account_id');
        $company->withholding_tax_account_id = $request->input('withholding_tax_account_id');
        $company->vat_account_id = $request->input('vat_account_id');
        $company->adjustment_in_account_id = $request->input('adjustment_in_account_id');
        $company->adjustment_out_account_id = $request->input('adjustment_out_account_id');
        $company->discount_account_id = $request->input('discount_account_id');

        //update  based on the http json body that is sent
        $company->update();

        return ( new Reference( $company ) )
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
}
