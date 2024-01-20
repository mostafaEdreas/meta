<?php

use App\Http\Controllers\Web\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['controller'=> ProductController::class,'middleware'=>'IsAdmin','prefix'=>'products'],function(){
    Route::post('/changeImg','changeImg')->name('product.changeImg');
    Route::get('/', 'index')->name('product.index');
    // Route::get('/show','show')->name('product.show');
    Route::post('/distroy/{id}', 'distroy')->name('product.distroy');
    Route::post('/restore/{id}','restore')->name('product.restore');
    // Route::get('/create','create')->name('product.create');
    Route::post('/store','store')->name('product.store');
   

});