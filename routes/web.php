<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return 'Welcome to basic GitHub user API.';
});

Route::post('register', 'App\Http\Controllers\RegisterController@register');
Route::post('login', 'App\Http\Controllers\LoginController@login');
