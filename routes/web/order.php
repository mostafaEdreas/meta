<?php

use App\Http\Controllers\Web\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['controller'=> OrderController::class,'middleware'=>'auth','prefix'=>'orders'],function(){
    Route::get('/', 'index')->name('order.index');
    Route::get('/show','show')->name('order.show');
});