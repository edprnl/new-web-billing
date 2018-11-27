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
   //List single user
   Route::get('department/{id}', 'References\DepartmentsController@show');
   //Create new user
   Route::post('department', 'References\DepartmentsController@create');
   //Update user
   Route::put('department/{id}', 'References\DepartmentsController@update');
   //Delete user
   Route::delete('department/{id}', 'References\DepartmentsController@delete');
   // END USER

   //List natures
   Route::get('natures', 'References\NatureOfBusinessController@index');
   //List single nature
   Route::get('nature/{id}', 'References\NatureOfBusinessController@show');
   //Create new nature
   Route::post('nature', 'References\NatureOfBusinessController@create');
   //Update nature
   Route::put('nature/{id}', 'References\NatureOfBusinessController@update');
   //Delete nature
   Route::delete('nature/{id}', 'References\NatureOfBusinessController@delete');
   // END nature

   //List locations
   Route::get('locations', 'References\LocationsController@index');
   //List single location
   Route::get('location/{id}', 'References\LocationsController@show');
   //Create new location
   Route::post('location', 'References\LocationsController@create');
   //Update location
   Route::put('location/{id}', 'References\LocationsController@update');
   //Delete location
   Route::delete('location/{id}', 'References\LocationsController@delete');
   // END location

   //List contract_types
   Route::get('contract_types', 'References\ContractTypesController@index');
   //List single contract_type
   Route::get('contract_type/{id}', 'References\ContractTypesController@show');
   //Create new contract_type
   Route::post('contract_type', 'References\ContractTypesController@create');
   //Update contract_type
   Route::put('contract_type/{id}', 'References\ContractTypesController@update');
   //Delete contract_type
   Route::delete('contract_type/{id}', 'References\ContractTypesController@delete');
   // END contract_type

   //List months
   Route::get('months', 'References\MonthsController@index');
   //List single month
   Route::get('month/{id}', 'References\MonthsController@show');
   //Create new month
   Route::post('month', 'References\MonthsController@create');
   //Update month
   Route::put('month/{id}', 'References\MonthsController@update');
   //Delete month
   Route::delete('month/{id}', 'References\MonthsController@delete');
   // END month

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
