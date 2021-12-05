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
    Route::get('image/{id}', 'ImageController@getImageId');

    //Get All Stikers
    Route::get('stikers', 'StikerController@allStikers');
    Route::get('stiker/{id}', 'StikerController@getStikerId');

    Route::group(['middleware' => ['auth.guard:api'],], function () {
        Route::post('logout', 'AuthController@logout');

        // Images Routes
        Route::post('save-image', 'ImageController@saveImage');
        Route::get('images/{id}', 'ImageController@userImage');
        Route::delete('images/{id}/delete', 'ImageController@deleteImage');

        //Stikers Routes
        Route::post('save-stikers', 'StikerController@saveStiker');
        Route::get('stikers/{id}', 'StikerController@userStiker');
        Route::delete('stikers/{id}/delete', 'StikerController@deleteStiker');
    });


});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
