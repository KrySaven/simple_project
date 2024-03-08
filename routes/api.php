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
Route::post('login', 'API\UserController@login');
Route::post('get_branch_by_dealer', 'API\UserController@get_branch_by_dealer');
Route::post('get_dealer_by_salesman', 'API\UserController@get_dealer_by_salesman');
Route::post('get_transaction', 'API\UserController@get_transaction');
Route::post('register', 'API\UserController@register');

Route::post('details', 'API\UserController@details');
Route::group(['middleware' => 'auth:api'], function(){
});