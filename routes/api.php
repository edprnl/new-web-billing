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
   Route::put('charge/delete/{id}', 'References\ChargesController@delete');
   //Check if charge was used
   Route::get('charge/check/{id}', 'References\ChargesController@checkIfUsed');
   // Charges

   //List billing period
   Route::get('periods', 'References\BillingPeriodController@index');
   //List billing period
   Route::get('period/{id}', 'References\BillingPeriodController@show');
   //Create billing period
   Route::post('period', 'References\BillingPeriodController@create');
   //Update billing period
   Route::put('period/{id}', 'References\BillingPeriodController@update');
   //Delete billing period
   Route::put('period/delete/{id}', 'References\BillingPeriodController@delete');
   //Check if billing period was used
   Route::get('period/check/{id}', 'References\BillingPeriodController@checkIfUsed');
   // END billing period

   //List check types
   Route::get('check_types', 'References\CheckTypeController@index');
   //List check type
   Route::get('check_type/{id}', 'References\CheckTypeController@show');
   //Create check type
   Route::post('check_type', 'References\CheckTypeController@create');
   //Update check type
   Route::put('check_type/{id}', 'References\CheckTypeController@update');
   //Delete check type
   Route::put('check_type/delete/{id}', 'References\CheckTypeController@delete');
   //Check if check type was used
   Route::get('check_type/check/{id}', 'References\CheckTypeController@checkIfUsed');
   // END check types

   Route::get('charges', 'References\ChargesController@index');
   //List categories
   Route::get('categories', 'References\CategoriesController@index');
   //List single category
   Route::get('category/{id}', 'References\CategoriesController@show');
   //Create new category
   Route::post('category', 'References\CategoriesController@create');
   //Update category
   Route::put('category/{id}', 'References\CategoriesController@update');
   //Delete category
   Route::put('category/delete/{id}', 'References\CategoriesController@delete');
   //Check if category was used
   Route::get('category/check/{id}', 'References\CategoriesController@checkIfUsed');
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
   Route::put('department/delete/{id}', 'References\DepartmentsController@delete');
   //Check if department was used
   Route::get('department/check/{id}', 'References\DepartmentsController@checkIfUsed');
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
   Route::put('location/delete/{id}', 'References\LocationsController@delete');
   //Check if location was used
   Route::get('location/check/{id}', 'References\LocationsController@checkIfUsed');
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
   Route::put('natureofbusiness/delete/{id}', 'References\NatureOfBusinessController@delete');
   //Check if nature was used
   Route::get('natureofbusiness/check/{id}', 'References\NatureOfBusinessController@checkIfUsed');
   // END natures

   //List contract types
   Route::get('contracttypes', 'References\ContractTypesController@index');
   //List single contract type
   Route::get('contracttype/{id}', 'References\ContractTypesController@show');
   //Create new contract type
   Route::post('contracttype', 'References\ContractTypesController@create');
   //Update contract type
   Route::put('contracttype/{id}', 'References\ContractTypesController@update');
   //Delete contract type
   Route::put('contracttype/delete/{id}', 'References\ContractTypesController@delete');
   //Check if contract type was used
   Route::get('contracttype/check/{id}', 'References\ContractTypesController@checkIfUsed');
   // END contract type

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
    Route::put('tenant/delete/{id}', 'References\TenantsController@delete');
    //Check if department was used
    Route::get('tenant/check/{id}', 'References\TenantsController@checkIfUsed');
    // END TENANT
     //---------------------------------- REFERENCES -----------------------------------------------

     //---------------------------------- UTILITIES ------------------------------------------------
    //USER
    //List users
    Route::get('users', 'Utilities\UsersController@index');
    //List single user
    Route::get('user/{id}', 'Utilities\UsersController@show');
    //Create new user
    Route::post('user', 'Utilities\UsersController@create');
    //Update user
    Route::put('user/{id}', 'Utilities\UsersController@update');
    //Delete user
    Route::put('user/delete/{id}', 'Utilities\UsersController@delete');
    // END USER
     //---------------------------------- UTILITIES ------------------------------------------------

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
    Route::put('contract/delete/{id}', 'Transactions\ContractsController@delete');
    //Check if contract was used
    Route::get('contract/check/{id}', 'Transactions\ContractsController@checkIfUsed');
    // END CONTRACT

    //List Billings
    Route::get('billings', 'Transactions\BillingsController@index');
    Route::get('billings/{period_id}/{department_id}', 'Transactions\BillingsController@index');
    //schedule and charges of billing
    Route::get('billingSC/sc/{id}', 'Transactions\BillingsController@scheduleAndCharges');
    Route::get('billing/balance/{tenant_id}', 'Transactions\BillingsController@getBillingBalance');
    //List single billing
    Route::get('billing/{id}', 'Transactions\BillingsController@show');
    Route::get('billing/{id}/{soa}', 'Transactions\BillingsController@show');
    Route::get('billing/{month_id}/{app_year}/{tenant_id}', 'Transactions\BillingsController@prevBalance');
    //Create new contract
    Route::post('billing', 'Transactions\BillingsController@create');
    //Update billing
    Route::put('billing/{id}', 'Transactions\BillingsController@update');
    //Delete billing
    Route::put('billing/delete/{id}', 'Transactions\BillingsController@delete');
    //Check if billing was used
    Route::get('billing/check/{id}', 'Transactions\BillingsController@checkIfUsed');
    // END billing

    //PAYMENTS
    //List payments
    Route::get('payments', 'Transactions\PaymentsController@index');
    //List single payment
    Route::get('payment/{id}', 'Transactions\PaymentsController@show');
    //Create new payment
    Route::post('payment', 'Transactions\PaymentsController@create');
    //Update payment
    Route::put('payment/{id}', 'Transactions\PaymentsController@update');
    //Delete payment
    Route::put('payment/delete/{id}', 'Transactions\PaymentsController@delete');
    //Check if payment was used
    Route::get('payment/check/{id}', 'Transactions\PaymentsController@checkIfUsed');
    // END PAYMENTS
    //---------------------------------- TRANSACTIONS ---------------------------------------------
});
