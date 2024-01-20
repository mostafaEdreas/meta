<?php

use App\Http\Controllers\Web\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['controller'=> OrderController::class,'middleware'=>'auth','prefix'=>'orders'],function(){
    Route::get('/', 'index')->name('order.index');
    Route::get('/show/{id}','show')->name('order.show');
    Route::get('/create', 'create')->name('order.create');
    Route::post('/store','store')->name('order.store');
    Route::get('/edit/{id}','edit')->name('order.edit');
    Route::post('/update/{id}','update')->name('order.update');

});