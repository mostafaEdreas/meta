<?php

use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['controller'=> UserController::class,'middleware'=>'auth','prefix'=>'users'],function(){
    Route::post('/changeImg','changeImg')->name('user.changeImg');
    Route::middleware('IsAdmin')->group(function(){
        Route::get('/', 'index')->name('user.index');
        Route::get('/show','show')->name('user.show');
        Route::post('/distroy/{id}', 'distroy')->name('user.distroy');
        Route::post('/restore/{id}','restore')->name('user.restore');
        Route::get('/create','create')->name('user.create');
        Route::post('/store','store')->name('user.store');
    });

});