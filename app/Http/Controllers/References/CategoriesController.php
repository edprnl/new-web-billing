<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RefCategory;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = RefCategory::where('is_deleted', 0)->orderBy('category_id', 'desc')->get();
        return Reference::collection($categories);
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
                'category_code' => 'required'
            ]
        )->validate();


        //return json based from the resource data
        return ( new Reference( RefCategory::create( $request->all() ) ) )
                ->response()
                ->setStatusCode(201);
        // $category = new RefCategory;
        // $category->category_code = $request->input('category_code');
        // $category->category_desc = $request->input('category_desc');
        // $category->created_datetime = Carbon::now();
        // // $category->created_by = Auth::user()->id;
        
        // if($category->save()){
        //     $response['success'] = "Success";
        //     $response['message'] = "Success";
        //     return $response;
        // }
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
        $category = RefCategory::findOrFail($id);

        return ( new Reference( $category ) )
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
        $category = RefCategory::findOrFail($id);

        Validator::make($request->all(),
            [
                'category_code' => 'required'
            ]
        )->validate();

        //update classification based on the http json body that is sent
        $category->update( $request->all() );

        return ( new Reference( $category ) )
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
