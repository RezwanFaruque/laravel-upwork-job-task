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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->middleware('api')->group(function () {

    Route::post('/register','UserAuthController@register')->name('user.registration');

    Route::post('/confirm-pin','UserAuthController@confrimPin')->name('user.confirmpin');

    Route::post('/login','UserAuthController@login')->name('user.login');

    
});

Route::namespace('Api')->middleware('auth:api')->group(function(){

    Route::post('/profile-update','UserAuthController@updateProfile')->name('user.updateprofile');
});

Route::namespace('Api')->middleware(['auth:api','admin'])->group(function(){
    //admin user routs
    
    Route::post('/send-invitationlink','UserAuthController@sendRegisterInvitation')->name('admin.sendregisterinvitation');
});


