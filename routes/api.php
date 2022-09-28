<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['cors', 'json.response']], function () {
    // User Authentication
    Route::post('login', 'AuthApiController@login');
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('addresses', 'AddressController');

        // Access Control
        Route::apiResource('users', 'StaffController');
        Route::apiResource('roles', 'RoleController');
        Route::apiResource('modules', 'ModuleController');
        Route::apiResource('categories', 'CategoryController');
        Route::apiResource('classifications', 'ClassificationController');
        Route::apiResource('brands', 'BrandController');
        Route::apiResource('tags', 'TagController');
        Route::apiResource('images', 'ImageController');
        Route::apiResource('products', 'ProductController');
        Route::apiResource('carts', 'CartController');
        Route::apiResource('cartItems', 'CartItemController');
        Route::apiResource('orders', 'OrderController');
        Route::apiResource('internalOrders', 'InternalOrderController');
        Route::get('permissions', 'PermissionController@index');
    });

    Route::get('public/products', 'ProductController@display');
    Route::get('public/tags', 'PublicAccessController@tags');
    Route::get('public/classifications', 'PublicAccessController@classifications');
    Route::get('public/categories', 'PublicAccessController@categories');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@ncdmb.gov.ng'
    ], 404);
});
