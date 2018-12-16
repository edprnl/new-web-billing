<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\References\CheckType;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;

class CheckTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $check_types = CheckType::where('is_deleted', 0)->orderBy('check_type_id', 'desc')->get();
        return Reference::collection($check_types);
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
                'check_type_code' => 'required',
                'check_type_desc' => 'required'
            ]
        )->validate();

        $check_type = new CheckType();
        $check_type->check_type_code = $request->input('check_type_code');
        $check_type->check_type_desc = $request->input('check_type_desc');
        $check_type->created_datetime = Carbon::now();
        $check_type->created_by = Auth::user()->id;
    
        $check_type->save();

        //return json based from the resource data
        return ( new Reference( $check_type ))
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
        $check_type = CheckType::findOrFail($id);

        return ( new Reference( $check_type ) )
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
        $check_type = CheckType::findOrFail($id);

        Validator::make($request->all(),
            [
                'check_type_code' => 'required',
                'check_type_desc' => 'required'
            ]
        )->validate();

        
        $check_type->check_type_code = $request->input('check_type_code');
        $check_type->check_type_desc = $request->input('check_type_desc');
        $check_type->modified_datetime = Carbon::now();
        $check_type->modified_by = Auth::user()->id;


        //update  based on the http json body that is sent
        $check_type->update();

        return ( new Reference( $check_type ) )
            ->response()
            ->setStatusCode(200);
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
        $check_type = CheckType::findOrFail($id);
        $check_type->is_deleted = 1;
        $check_type->deleted_datetime = Carbon::now();
        $check_type->deleted_by = Auth::user()->id;

        //update classification based on the http json body that is sent
        $check_type->save();

        return ( new Reference( $check_type ) )
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

        if(PaymentInfo::where('check_type_id', '=', $id)
            ->where('is_deleted', 0)
            ->exists()) {
            $exists = 'true';
        }
        
        return $exists;
    }
}
