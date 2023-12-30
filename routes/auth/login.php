<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(['controller'=> LoginController::class,'prefix'=>'login'],function(){
    Route::get('/', [LoginController::class,'index'])->name('login')->middleware('guest');
    Route::post('/login', [LoginController::class,'login'])->name('login.check')->middleware('guest');
    Route::get('/logout', [LoginController::class,'logout'])->name('logout')->middleware('auth');
});