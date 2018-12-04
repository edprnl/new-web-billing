<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\References\Charges;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;

class ChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charges = Charges::leftJoin('account_titles', 'account_titles.account_id', '=', 'b_refcharges.account_id')
                        ->where('b_refcharges.is_deleted', 0)->orderBy('charge_id', 'desc')->get();
        return Reference::collection($charges);
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
                'charge_code' => 'required',
                'charge_desc' => 'required'
            ]
        )->validate();

        $charge = new Charges();
        $charge->charge_code = $request->input('charge_code');
        $charge->charge_desc = $request->input('charge_desc');
        $charge->account_id = $request->input('account_id');
        $charge->created_datetime = Carbon::now();
        $charge->created_by = Auth::user()->id;

        $charge->save();

        //return json based from the resource data
        return ( new Reference( $charge ))
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
        $charge = Charges::findOrFail($id);

        return ( new Reference( $charge ) )
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
                'charge_code' => 'required',
                'charge_desc' => 'required'
            ]
        )->validate();

        $charge = Charges::findOrFail($id);
        $charge->charge_code = $request->input('charge_code');
        $charge->charge_desc = $request->input('charge_desc');
        $charge->account_id = $request->input('account_id');
        $charge->modified_datetime = Carbon::now();
        $charge->modified_by = Auth::user()->id;

        $charge->save();

        //return json based from the resource data
        return ( new Reference( $charge ))
                ->response()
                ->setStatusCode(201);
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
