<?php

use App\Http\Controllers\Admin\Api\AuthApiController;
use App\Http\Controllers\Admin\Api\SystemApiController;
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


Route::group(['namespace' => 'Admin'],function (){
    ################### auth ##############
    Route::post('/login',[AuthApiController::class,'login']);
    Route::post('/register',[AuthApiController::class,'register']);
    ######################################
    Route::get('/sliders',[SystemApiController::class,'sliders']);
    Route::get('/in-boarding',[SystemApiController::class,'inBordingData']);
    Route::post('/check-verification-code',[SystemApiController::class,'checkVerificationCode']);
    Route::post('/resend-code',[SystemApiController::class,'resendCode']);
    Route::get('/settings',[SystemApiController::class,'settings']);
});

Route::group(['middleware' => 'JwtMiddleware' ,'namespace' => 'Admin'],function (){
    Route::get('/user-data',[SystemApiController::class,'getUserData']);
    Route::get('/logout',[AuthApiController::class,'logout']);
    Route::post('/update-profile',[SystemApiController::class,'updateProfile']);
    Route::post('/contact-us',[SystemApiController::class,'contactUs']);
    Route::get('/branches',[SystemApiController::class,'branches']);
    ################## cart ##################
    Route::post('/cart-data',[SystemApiController::class,'cartData']);
    Route::post('/add-cart',[SystemApiController::class,'addCart']);
    Route::post('/update-cart',[SystemApiController::class,'updateCart']);
    Route::post('/delete-cart',[SystemApiController::class,'deleteCart']);
    ############ order ###############
    Route::post('/create-order',[SystemApiController::class,'createOrder']);
    Route::get('/my-orders',[SystemApiController::class,'myOrders']);
    Route::post('/orders-details',[SystemApiController::class,'ordersDetails']);
    Route::get('/notifications',[SystemApiController::class,'userNotifacations']);
    Route::get('/pay-order',[SystemApiController::class,'payOrder']);
    Route::post('/delete-account',[SystemApiController::class,'deleteAccount']);
});
