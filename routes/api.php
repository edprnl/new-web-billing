<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

   //List departments
   Route::get('departments', 'DepartmentController@index');
   //List single user
   Route::get('department/{id}', 'DepartmentController@show');
   //Create new user
   Route::post('department', 'DepartmentController@create');
   //Update user
   Route::put('department/{id}', 'DepartmentController@update');
   //Delete user
   Route::delete('department/{id}', 'DepartmentController@delete');
   // END USER
