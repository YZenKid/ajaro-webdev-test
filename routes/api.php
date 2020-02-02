<?php

use App\TrxPurchase;
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

Route::post('v1/login', 'Api\V1\AuthController@login');
Route::group(['middleware' => ['auth:api'], 'namespace' => 'Api\V1', 'prefix' => 'v1'], function () {
    Route::get('user', 'AuthController@user');
    Route::post('logout', 'AuthController@logout');
    Route::apiResources([
        'users'         => 'UserController',
        'suppliers'     => 'SupplierController',
        'categories'    => 'CategoryController',
        'products'      => 'ProductController',
        'trx_purchase'  => 'TrxPurchaseController',
        'trx_sale'      => 'TrxSaleController',
    ]);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'resource not found'
    ], 404);
});
