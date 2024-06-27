<?php

use App\Http\Controllers\Admin\BranchesController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PrintingTypeController;
use App\Http\Controllers\Admin\PrintPriceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\AdminPermitionsController;
use App\Http\Controllers\Admin\OnbordingController;


Route::group(['namespace' => 'Admin', 'middleware' => 'auth:admin', 'prefix' => 'admin'], function () {
    Route::get('/dashboard',[DashboardController::class,'getDashboard'])->name('dashboard.index');
    Route::post('/logout',[LoginController::class,'logout'])->name('admin.logout');
    ########################## profile ###############################
    Route::get('/profile',[AdminController::class,'profile'])->name('admin.profile');
    Route::post('/update-profile',[AdminController::class,'updateProfile'])->name('admin.update.profile');
    ####################### admins #########################
    Route::get('/admins',[AdminController::class,'index'])->name('admin.admins.index');
    Route::get('/create-admin',[AdminController::class,'create'])->name('admin.admins.create');
    Route::post('/store-admin',[AdminController::class,'store'])->name('admin.admins.store');
    Route::post('/update-admin/{id}',[AdminController::class,'update'])->name('admin.admins.update');
    Route::get('/edit-admin/{id}',[AdminController::class,'edit'])->name('admin.admins.edit');
    Route::post('/delete-admin',[AdminController::class,'delete'])->name('admin.admins.delete');
    Route::post('/update-admin-status',[AdminController::class,'updateStatus'])->name('admin.admins.update.status');
    Route::post('/store-emp-admin',[AdminController::class,'storeEmployee'])->name('store.emp.admin');
    Route::post('/edit-emp-admin',[AdminController::class,'editEmployee'])->name('edit.emp.admin');
    Route::get('/admin-permissions/{id}',[AdminPermitionsController::class,'adminPermitions'])->name('admin.permissions.index');
    Route::post('/admin-save-permissions/{id}',[AdminPermitionsController::class,'saveAdminPermissions'])->name('admin.save.permissions');
    ####################### slider #########################
    Route::get('/sliders',[SliderController::class,'index'])->name('admin.sliders.index');
    Route::get('/create-slider',[SliderController::class,'create'])->name('admin.sliders.create');
    Route::post('/store-slider',[SliderController::class,'store'])->name('admin.sliders.store');
    Route::get('/edit-slider/{id}',[SliderController::class,'edit'])->name('admin.sliders.edit');
    Route::post('/update-slider/{id}',[SliderController::class,'update'])->name('admin.sliders.update');
    Route::post('/delete-slider',[SliderController::class,'delete'])->name('admin.sliders.delete');
    Route::post('/update-slider-status',[SliderController::class,'updateStatus'])->name('admin.sliders.update.status');
    ####################### deals status #########################
    Route::get('/branches-status',[BranchesController::class,'index'])->name('admin.branches.index');
    Route::get('/create-branches',[BranchesController::class,'create'])->name('admin.branches.create');
    Route::post('/store-branches',[BranchesController::class,'store'])->name('admin.branches.store');
    Route::get('/edit-branches/{id}',[BranchesController::class,'edit'])->name('admin.branches.edit');
    Route::post('/update-branches/{id}',[BranchesController::class,'update'])->name('admin.branches.update');
    Route::post('/delete-branch',[BranchesController::class,'delete'])->name('admin.branches.delete');
    Route::post('/update-branches-status',[BranchesController::class,'updateStatus'])->name('admin.branches.update.status');
    ####################### slider #########################
    Route::get('/onbording',[OnbordingController::class,'index'])->name('admin.onbording.index');
    Route::get('/create-onbording',[OnbordingController::class,'create'])->name('admin.onbording.create');
    Route::post('/store-onbording',[OnbordingController::class,'store'])->name('admin.onbording.store');
    Route::get('/edit-onbording/{id}',[OnbordingController::class,'edit'])->name('admin.onbording.edit');
    Route::post('/update-onbording/{id}',[OnbordingController::class,'update'])->name('admin.onbording.update');
    Route::post('/delete-onbording',[OnbordingController::class,'delete'])->name('admin.onbording.delete');
    Route::post('/update-onbording-status',[OnbordingController::class,'updateStatus'])->name('admin.onbording.update.status');
    ####################### printing_type #########################
    Route::get('/printing-type',[PrintingTypeController::class,'index'])->name('admin.printing_type.index');
    Route::get('/create-printing-type',[PrintingTypeController::class,'create'])->name('admin.printing_type.create');
    Route::post('/store-printing-type',[PrintingTypeController::class,'store'])->name('admin.printing_type.store');
    Route::get('/edit-printing-type/{id}',[PrintingTypeController::class,'edit'])->name('admin.printing_type.edit');
    Route::post('/update-printing-type/{id}',[PrintingTypeController::class,'update'])->name('admin.printing_type.update');
    Route::post('/delete-printing-type',[PrintingTypeController::class,'delete'])->name('admin.printing_type.delete');
    ####################### printing_type #########################
    Route::get('/print-price',[PrintPriceController::class,'index'])->name('admin.print_price.index');
    Route::get('/create-print-price',[PrintPriceController::class,'create'])->name('admin.print_price.create');
    Route::post('/store-print-price',[PrintPriceController::class,'store'])->name('admin.print_price.store');
    Route::get('/edit-print-price/{id}',[PrintPriceController::class,'edit'])->name('admin.print_price.edit');
    Route::post('/update-print-price/{id}',[PrintPriceController::class,'update'])->name('admin.print_price.update');
    Route::post('/delete-print-price',[PrintPriceController::class,'delete'])->name('admin.print_price.delete');
    ####################### users #########################
    Route::get('/users',[UsersController::class,'index'])->name('admin.users.index');
    Route::get('/create-user',[UsersController::class,'create'])->name('admin.users.create');
    Route::post('/store-user',[UsersController::class,'store'])->name('admin.users.store');
    Route::get('/edit-user/{id}',[UsersController::class,'edit'])->name('admin.users.edit');
    Route::post('/update-user/{id}',[UsersController::class,'update'])->name('admin.users.update');
    Route::post('/delete-user',[UsersController::class,'delete'])->name('admin.users.delete');
    Route::post('/update-user-status',[UsersController::class,'updateStatus'])->name('admin.users.update.status');
    ###################### contactus ########################
    Route::get('/contact-us',[ContactUsController::class,'index'])->name('admin.contacts.index');
    Route::get('/contact-us/{id}',[ContactUsController::class,'show'])->name('admin.contacts.show');
    Route::post('/update-contact-us/{id}',[ContactUsController::class,'update'])->name('admin.contacts.update');
    Route::post('/delete-contact',[ContactUsController::class,'delete'])->name('admin.contacts.delete');
    ###################### settings ########################
    Route::get('/settings',[SettingsController::class,'settings'])->name('admin.settings.edit');
    Route::post('/setting-update',[SettingsController::class,'update'])->name('admin.settings.update');
    ############### orders ##################
    Route::get('/orders',[OrderController::class,'index'])->name('admin.orders.index');
    Route::get('/show-order/{id}',[OrderController::class,'show'])->name('admin.orders.show');
    Route::post('/delete-order',[OrderController::class,'delete'])->name('admin.orders.delete');
    Route::post('/order-send-sms',[OrderController::class,'sendOrderSms'])->name('admin.orders.send_sms');
    Route::post('/update-order-status',[OrderController::class,'updateStatus'])->name('admin.orders.update_status');
});
Route::get('admin/login',[LoginController::class,'getLoginForm'])->name('get.admin.login');
Route::post('admin/login',[LoginController::class,'login'])->name('admin.login');
