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
   // List of Charges
   Route::get('charges', 'References\ChargesController@index');
   // List of Account
   Route::get('accounts', 'References\AccountTitlesController@index');
   //List single charge
   Route::get('charge/{id}', 'References\ChargesController@show');
   //Create new category
   Route::post('charge', 'References\ChargesController@create');
   //Update charge
   Route::put('charge/{id}', 'References\ChargesController@update');
   //Delete charge
   Route::delete('charge/{id}', 'References\ChargesController@delete');
   // Charges

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
   // END locations

   //List natures
   Route::get('natureofbusinesses', 'References\NatureOfBusinessController@index');
   //List single nature
   Route::get('natureofbusiness/{id}', 'References\NatureOfBusinessController@show');
   //Create new nature
   Route::post('natureofbusiness', 'References\NatureOfBusinessController@create');
   //Update nature
   Route::put('natureofbusiness/{id}', 'References\NatureOfBusinessController@update');
   //Delete nature
   Route::delete('natureofbusiness/{id}', 'References\NatureOfBusinessController@delete');
   // END natures

   //List contract types
   Route::get('contracttypes', 'References\ContractTypesController@index');
   //List single contract type
   Route::get('contracttype/{id}', 'References\ContractTypesController@show');
   //Create new nature
   Route::post('contracttype', 'References\ContractTypesController@create');
   //Update nature
   Route::put('contracttype/{id}', 'References\ContractTypesController@update');
   //Delete nature
   Route::delete('contracttype/{id}', 'References\ContractTypesController@delete');
   // END natures

   //MONTHS
   Route::get('months', 'References\MonthsController@index');
   //END MONTHS

   //TENANT
    //List tenants
    Route::get('tenants', 'References\TenantsController@index');
    //List single tenant
    Route::get('tenant/{id}', 'References\TenantsController@show');
    //Create new tenant
    Route::post('tenant', 'References\TenantsController@create');
    //Update tenant
    Route::put('tenant/{id}', 'References\TenantsController@update');
    //Delete tenant
    Route::delete('tenant/{id}', 'References\TenantsController@delete');
    // END TENANT


     //---------------------------------- REFERENCES -----------------------------------------------

     //---------------------------------- TRANSACTIONS ---------------------------------------------
    //CONTRACT INFO
    //List contracts
    Route::get('contracts', 'Transactions\ContractsController@index');
    Route::get('contracts/{tenant_id}', 'Transactions\ContractsController@index');
    //List Schedules and Charges
    Route::get('contracts/sc/{id}', 'Transactions\ContractsController@scheduleAndCharges');
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

    //List Billings
    Route::get('billings', 'Transactions\BillingsController@index');
    Route::get('billings/{period_id}', 'Transactions\BillingsController@index');
    //schedule and charges of billing
    Route::get('billings/sc/{id}', 'Transactions\BillingsController@scheduleAndCharges');
    //List single billing
    Route::get('billing/{id}', 'Transactions\BillingsController@show');
    Route::get('billing/{month_id}/{app_year}/{tenant_id}', 'Transactions\BillingsController@prevBalance');
    //Create new contract
    Route::post('billing', 'Transactions\BillingsController@create');
    //Update billing
    Route::put('billing/{id}', 'Transactions\BillingsController@update');
    //Delete billing
    Route::delete('billing/{id}', 'Transactions\BillingsController@delete');
    // END billing

    //---------------------------------- TRANSACTIONS ---------------------------------------------
});
