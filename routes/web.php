<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => ['guest', 'cors', 'json.response']], function () {
    Route::get('products/public', 'ProductController@display');
    Route::get('tags/public', 'PublicAccessController@tags');
    Route::get('classifications/public', 'PublicAccessController@classifications');
    Route::get('categories/public', 'PublicAccessController@categories');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
