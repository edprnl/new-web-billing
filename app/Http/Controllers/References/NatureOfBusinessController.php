<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\References\NatureOfBusiness;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;

class NatureOfBusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$departments = Department::where('is_deleted', 0)->orderBy('department_id', 'asc')->paginate(5);
        $natureofbusiness = NatureOfBusiness::where('is_deleted', 0)->orderBy('nature_of_business_id', 'desc')->get();
        return Reference::collection($natureofbusiness);
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
                'nature_of_business_code' => 'required',
                'nature_of_business_desc' => 'required'
            ]
        )->validate();

        $naturebusiness = new NatureOfBusiness();
        $naturebusiness->nature_of_business_code = $request->input('nature_of_business_code');
        $naturebusiness->nature_of_business_desc = $request->input('nature_of_business_desc');
        $naturebusiness->created_datetime = Carbon::now();
        $naturebusiness->created_by = Auth::user()->id;
    
        $naturebusiness->save();

        //return json based from the resource data
        return ( new Reference( $naturebusiness ))
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
        $naturebusiness = NatureOfBusiness::findOrFail($id);

        return ( new Reference( $naturebusiness ) )
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
        $naturebusiness = NatureOfBusiness::findOrFail($id);

        Validator::make($request->all(),
            [
                'nature_of_business_code' => 'required',
                'nature_of_business_desc' => 'required'
            ]
        )->validate();

        $naturebusiness->nature_of_business_code = $request->input('nature_of_business_code');
        $naturebusiness->nature_of_business_desc = $request->input('nature_of_business_desc');
        $naturebusiness->modified_datetime = Carbon::now();
        $naturebusiness->modified_by = Auth::user()->id;


        //update  based on the http json body that is sent
        $naturebusiness->update();

        return ( new Reference( $naturebusiness ) )
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
        // $category = RefCategory::findOrFail($id);
        // $category->is_deleted = 1;
        // $category->deleted_datetime = Carbon::now();
        // $category->deleted_by = Auth::user()->id;

        // //update classification based on the http json body that is sent
        // $category->update();

        // return ( new Reference( $category ) )
        //     ->response()
        //     ->setStatusCode(200);
    }
}
