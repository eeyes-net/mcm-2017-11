<?php

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
    return view('welcome');
});
Route::get('login', 'Auth\OAuthLoginController@login')->name('login');
Route::get('login/admin', 'Auth\OAuthLoginController@loginAdmin');
Route::get('login/callback', 'Auth\OAuthLoginController@callback');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::view('admin/{path?}', 'admin.admin')
    ->where('path', '.*')
    ->middleware(['auth', 'admin']);
