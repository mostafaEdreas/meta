<?php

use App\Http\Controllers\Web\PurchaseController;
use App\Models\Purchase;
use Illuminate\Support\Facades\Route;

Route::group(['controller'=> PurchaseController::class,'middleware'=>'auth','prefix'=>'purchases'],function(){
    Route::get('/', 'index')->name('purchase.index');
    Route::get('/show/{id}','show')->name('purchase.show');
    Route::get('/create','create')->name('purchase.create');
    Route::post('/store','store')->name('purchase.store');
    Route::get('/edit/{id}','edit')->name('purchase.edit');
    Route::post('/update/{id}','update')->name('purchase.update');
});