<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    // User Authentication
    Route::post('login', 'AuthApiController@login');
    Route::apiResource('addresses', 'AddressController');

    // Access Control
    Route::apiResource('roles', 'RoleController');
    Route::get('permissions', 'PermissionController@index');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@ncdmb.gov.ng'
    ], 404);
});
