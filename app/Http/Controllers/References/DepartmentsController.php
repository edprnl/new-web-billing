<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$departments = Department::where('is_deleted', 0)->orderBy('department_id', 'asc')->paginate(5);
        $departments = Department::where('is_deleted', 0)->orderBy('department_id', 'desc')->get();
        return Reference::collection($departments);
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
                'department_code' => 'required',
                'department_desc' => 'required'
            ]
        )->validate();

        $department = new Department();
        $department->department_code = $request->input('department_code');
        $department->department_desc = $request->input('department_desc');
        $department->created_datetime = Carbon::now();
        $department->created_by = Auth::user()->id;
    
        $department->save();

        //return json based from the resource data
        return ( new Reference( $department ))
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
        $department = Department::findOrFail($id);

        return ( new Reference( $department ) )
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
        $department = Department::findOrFail($id);

        Validator::make($request->all(),
            [
                'department_code' => 'required',
                'department_desc' => 'required'
            ]
        )->validate();

        
        $department->department_code = $request->input('department_code');
        $department->department_desc = $request->input('department_desc');
        $department->modified_datetime = Carbon::now();
        $department->modified_by = Auth::user()->id;


        //update  based on the http json body that is sent
        $department->update();

        return ( new Reference( $department ) )
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
