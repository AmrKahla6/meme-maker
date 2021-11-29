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


    Route::group(['middleware' => ['auth.guard:api'],], function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('profile/{user}', 'AuthController@profile');
        Route::post('update-profile', 'AuthController@updateProfile');
    });


});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
