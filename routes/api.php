<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['cors', 'json.response']], function () {
    // User Authentication
    Route::post('login', 'AuthApiController@login');
    Route::apiResource('addresses', 'AddressController');

    // Access Control
    Route::apiResource('roles', 'RoleController');
    Route::apiResource('modules', 'ModuleController');
    Route::apiResource('categories', 'CategoryController');
    Route::apiResource('classifications', 'ClassificationController');
    Route::apiResource('brands', 'BrandController');
    Route::apiResource('tags', 'TagController');
    Route::apiResource('images', 'ImageController');
    Route::apiResource('products', 'ProductController');
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
