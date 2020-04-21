<?php

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

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    // Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::post('user/create', 'UserController@store')->middleware('isAdmin');
    Route::post('user/password/{id}', 'UserController@changePassword')->middleware('isAdmin');
    Route::put('user/{id}', 'UserController@update')->middleware('isAdmin');
    Route::delete('user/{id}', 'UserController@destroy')->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
    
    //Bilty
    Route::get('bilty', 'BiltyController@index');
    Route::get('bilty/{id}', 'BiltyController@show');
    Route::put('bilty/{id}', 'BiltyController@update');
    Route::post('bilty/create', 'BiltyController@store');
    Route::post('bilty/{id}', 'BiltyController@destroy');
    Route::get('bilties/last', 'BiltyController@last');
    Route::post('bilties/status/{status}', 'BiltyController@changeStatus');
    Route::post('bilties/payment/{status}', 'BiltyController@changePaymentStatus');
    Route::get('bilties/customer/{id}', 'BiltyController@showCustomerBilties');
    Route::get('bilties/registered', 'BiltyController@showRegisteredBilties');
    Route::post('bilties/registeration/reset', 'BiltyController@resetRegisteredBilties');
    Route::get('bilties/challan/{id}', 'BiltyController@showChallanBilties');
    Route::get('bilties/total', 'BiltyController@totalCount');
    Route::get('bilties/total/month', 'BiltyController@monthlyTotal');
    
    Route::get('customer', 'CustomerController@index');
    Route::get('customer/paginate', 'CustomerController@paginate');
    Route::get('customer/sort', 'CustomerController@sort');
    Route::get('customer/search', 'CustomerController@search');
    Route::get('customer/last', 'CustomerController@last');
    Route::get('customer/{id}', 'CustomerController@show');
    Route::post('customer/create', 'CustomerController@store');
    Route::put('customer/{id}', 'CustomerController@update');
    Route::delete('customer/{id}', 'CustomerController@destroy');
    Route::get('customers/total', 'CustomerController@totalCount');
    Route::get('customers/top', 'CustomerController@topCustomer');
    
    Route::get('package/{id}', 'PackageController@show');
    
    Route::get('challan/last', 'ChallanController@last');
    Route::get('challan', 'ChallanController@index');
    Route::post('challan/create', 'ChallanController@store');
    Route::get('challan/{id}', 'ChallanController@show');
    Route::get('challan/truck_no/{id}', 'ChallanController@getTruckNo');
    Route::put('challan/{id}', 'ChallanController@update');
    Route::get('challans/total', 'ChallanController@totalCount');
    
    Route::get('sender/{id}', 'SenderController@show');
    Route::get('receiver/{id}', 'ReceiverController@show');
    
    Route::put('ledger/{id}', 'GeneralLedgerController@update');
    Route::get('ledger/{id}', 'GeneralLedgerController@show');
    Route::get('ledger', 'GeneralLedgerController@index');
    Route::get('ledgers/last', 'GeneralLedgerController@last');
    Route::get('ledgers/{customer_id}', 'GeneralLedgerController@showCustomerLedgers');
    Route::get('ledger/accounts/customer/{customer_id}', 'GeneralLedgerController@showCustomerAccounts');
    Route::post('ledger/create', 'GeneralLedgerController@store');
    Route::post('ledger/payment/{status}', 'GeneralLedgerController@changePaymentStatus');
    Route::get('ledger/paid/amount', 'GeneralLedgerController@showPaidAmount');
    Route::get('ledgers/pending/amount', 'GeneralLedgerController@showPendingLedgers');
    
    Route::get('sender', 'SenderController@index');
    Route::post('sender/create', 'SenderController@store');
    Route::delete('sender/{id}', 'SenderController@destroy');

    Route::get('receiver', 'ReceiverController@index');
    Route::post('receiver/create', 'ReceiverController@store');
    Route::delete('receiver/{id}', 'ReceiverController@destroy');
});
