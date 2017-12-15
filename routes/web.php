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

Route::get('login', 'Auth\OAuthLoginController@login')->name('login');
Route::get('login/callback', 'Auth\OAuthLoginController@callback')->name('login_callback');
Route::get('login/admin', 'Auth\OAuthLoginController@adminLogin')->name('admin_login');
Route::get('login/admin/callback', 'Auth\OAuthLoginController@adminCallback')->name('admin_login_callback');
Route::get('logout', 'Auth\OAuthLoginController@logout')->name('logout');
Route::post('logout', 'Auth\OAuthLoginController@logout')->name('logout');

Route::namespace('Index')->group(function () {
    Route::get('/', 'PostController@index');
    Route::get('post', 'PostController@index');
    Route::get('post/{id}', 'PostController@show');
    Route::get('match', 'MatchController@index');
    Route::get('match/{id}', 'MatchController@show');
    Route::post('match/{id}/apply', 'MatchController@apply');
    Route::get('recruit', 'RecruitController@index');
});


Route::view('admin/{path?}', 'admin.admin')
    ->where('path', '.*')
    ->middleware(['auth', 'admin']);
