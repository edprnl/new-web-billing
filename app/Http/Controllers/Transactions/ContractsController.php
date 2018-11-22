<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ContractInfo;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = ContractInfo::leftJoin('b_tenants', 'b_tenants.tenant_id', '=', 'b_contract_info.tenant_id')
                            ->leftJoin('b_refdepartments', 'b_refdepartments.department_id', '=', 'b_contract_info.department_id')
                            ->leftJoin('b_reflocations', 'b_reflocations.location_id', '=', 'b_contract_info.location_id')
                            ->leftJoin('b_refcontracttype', 'b_refcontracttype.contract_type_id', '=', 'b_contract_info.contract_type_id')
                            ->leftJoin('b_refcategory', 'b_refcategory.category_id', '=', 'b_contract_info.category_id')
                            ->where('b_contract_info.is_deleted', 0)
                            ->get();
        return Reference::collection($contracts);
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
