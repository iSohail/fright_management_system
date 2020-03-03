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
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');

    //Bilty
    Route::get('bilty', 'BiltyController@index');
    Route::get('bilty/{id}', 'BiltyController@show');
    Route::put('bilty/{id}', 'BiltyController@update');
    Route::post('bilty/create', 'BiltyController@store');
    Route::post('bilty/{id}', 'BiltyController@destroy');
    
    Route::get('customer', 'CustomerController@index');
    Route::get('customer/last', 'CustomerController@last');
    Route::post('customer/create', 'CustomerController@store');
    Route::put('customer/{id}', 'CustomerController@update');
    Route::delete('customer/{id}', 'CustomerController@destroy');
});
