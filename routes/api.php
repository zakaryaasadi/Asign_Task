<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/create_task','TaskController@createTask');
Route::get('/task','TestController@getAllTaskPerDay');
Route::get('/getAllTask','DedController@getAllTask');
Route::get('/createCoupon','DedController@requestAllCoupon');

Route::get('/test','TestController@test');
