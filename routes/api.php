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
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); 
Route::middleware('auth:api')->group(function () {

   //---------------------------------- REFERENCES -----------------------------------------------

   Route::get('charges', 'References\ChargesController@index');
   Route::get('periods', 'References\BillingPeriodController@index');
   //List categories
   Route::get('categories', 'References\CategoriesController@index');
   //List single category
   Route::get('category/{id}', 'References\CategoriesController@show');
   //Create new category
   Route::post('category', 'References\CategoriesController@create');
   //Update category
   Route::put('category/{id}', 'References\CategoriesController@update');
   //Delete category
   Route::delete('category/{id}', 'References\CategoriesController@delete');
   // END categories

   //List departments
   Route::get('departments', 'References\DepartmentsController@index');
   //List single departments
   Route::get('department/{id}', 'References\DepartmentsController@show');
   //Create new departments
   Route::post('department', 'References\DepartmentsController@create');
   //Update departments
   Route::put('department/{id}', 'References\DepartmentsController@update');
   //Delete departments
   Route::delete('department/{id}', 'References\DepartmentsController@delete');
   // END departments

   //TENANT
    //List tenants
    Route::get('tenants', 'References\TenantsController@index');
    //List single tenant
    Route::get('tenant/{id}', 'References\TenantsController@show');
    //Create new tenant
    Route::post('tenant', 'References\TenantsController@create');
    //Update tenant
    Route::put('tenant', 'References\TenantsController@update');
    //Delete tenant
    Route::delete('tenant/{id}', 'References\TenantsController@delete');
    // END TENANT


     //---------------------------------- REFERENCES -----------------------------------------------

    //CONTRACT INFO
    //List contracts
    Route::get('contracts', 'Transactions\ContractsController@index');
    //List Schedules and Charges
    Route::get('contracts/{id}', 'Transactions\ContractsController@scheduleAndCharges');
    //List Schedules and Charges for Billing
    Route::get('contracts/{id}/{app_year}/{month_id}', 'Transactions\ContractsController@scheduleAndChargesBilling');
    //List single contract
    Route::get('contract/{id}', 'Transactions\ContractsController@show');
    //Create new contract
    Route::post('contract', 'Transactions\ContractsController@create');
    //Update contract
    Route::put('contract/{id}', 'Transactions\ContractsController@update');
    //Delete contract
    Route::delete('contract/{id}', 'Transactions\ContractsController@delete');
    // END CONTRACT
});
