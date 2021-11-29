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
Route::group(['middleware' => ['api','changeLanguage'], 'namespace' => 'API'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('forgetpassword', 'AuthController@forgetpassword');
    Route::post('activcode', 'AuthController@activcode');
    Route::post('rechangepass', 'AuthController@rechangepass');

    //Get All Images
    Route::get('images', 'ImageController@allImages');

    Route::group(['middleware' => ['auth.guard:api'],], function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('save-image', 'ImageController@saveImage');
        Route::get('images/{id}', 'ImageController@userImage');

    });


});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
