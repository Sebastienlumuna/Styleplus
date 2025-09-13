<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;


Route::controller(RegisterController::class)->group(function (){
    Route::get('/register', 'index')->name('registerform');
    Route::post('/register', 'register')->name('register');
});

Route::controller(LoginController::class)->group(function (){
    Route::get('/login', 'index')->name('loginform');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});
