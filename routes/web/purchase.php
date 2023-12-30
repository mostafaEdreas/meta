<?php

use App\Http\Controllers\Web\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['controller'=> OrderController::class,'middleware'=>'auth','prefix'=>'purchases'],function(){
    Route::get('/', 'index')->name('purchase.index');
    Route::get('/show','show')->name('purchase.show');
});