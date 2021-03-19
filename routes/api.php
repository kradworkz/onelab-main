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

Route::post('/login', 'Api\AuthController@login');

Route::get('sync/agencytypes', 'Api\SyncableController@agencytypes');
Route::get('sync/agencies/{type}', 'Api\SyncableController@agencies');
Route::get('sync/dropdownlists/{type}', 'Api\SyncableController@dropdownlists');
Route::get('sync/testservices', 'Api\SyncableController@testservices');
Route::get('sync/packages', 'Api\SyncableController@packages');
Route::get('sync/sampletests', 'Api\SyncableController@sampletests');
Route::get('sync/methodreferences', 'Api\SyncableController@methodreferences');

Route::post('get/sampletypes', 'Api\ReferralController@getSampletypes');

Route::middleware(['auth:sanctum'])->group(function () {  
    
    Route::middleware('checkRequest')->group(function(){    

        Route::get('check', 'Api\ReferralController@check');
        Route::post('request', 'Api\ReferralController@store');
        
    });

});
