<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function () {
    Route::prefix('user')->middleware('auth')->group(function () {
        Route::get('/', 'UserController@show');
        Route::put('/', 'UserController@update');
    });
    Route::prefix('post')->group(function () {
        Route::get('/', 'PostController@index');
        Route::get('{post}', 'PostController@show');
    });
    Route::prefix('match')->group(function () {
        Route::get('/', 'MatchController@index');
        Route::get('{match}', 'MatchController@show');
        Route::post('{match}/apply', 'MatchController@apply')->middleware('auth');
    });
    Route::prefix('team')->group(function () {
        Route::get('/', 'TeamController@index')->middleware('auth');
        Route::post('/', 'TeamController@store')->middleware('auth');
    });
    Route::prefix('recruit')->group(function () {
        Route::get('/', 'RecruitController@index');
        Route::get('current_user', 'RecruitController@currentUser')->middleware('auth');
        Route::post('/', 'RecruitController@store')->middleware('auth');
        Route::put('{recruit}', 'RecruitController@update')->middleware('auth');
        Route::delete('{recruit}', 'RecruitController@destroy')->middleware('auth');
    });
    Route::prefix('admin')->namespace('Admin')->middleware(['auth', 'admin'])->group(function () {
        Route::prefix('post')->group(function () {
            Route::get('/', 'PostController@index');
            Route::post('/', 'PostController@store');
            Route::get('{post}', 'PostController@show');
            Route::put('{post}', 'PostController@update');
            Route::delete('{post}', 'PostController@destroy');
        });
        Route::prefix('user')->group(function () {
            Route::get('/', 'UserController@index');
            Route::get('{user}', 'UserController@show');
            Route::get('{user}/team', 'UserController@team');
            Route::put('{user}', 'UserController@update');
        });
        Route::prefix('team')->group(function () {
            Route::get('/', 'TeamController@index');
            Route::get('{team}', 'TeamController@show');
            Route::get('{team}/match', 'TeamController@match');
            Route::put('{team}', 'TeamController@update');
            Route::delete('{team}', 'TeamController@destroy');
        });
        Route::prefix('match')->group(function () {
            Route::get('/', 'MatchController@index');
            Route::post('/', 'MatchController@store');
            Route::get('{match}', 'MatchController@show');
            Route::get('{match}/team', 'MatchController@team');
            Route::post('{match}/apply', 'MatchController@apply');
            Route::delete('{match}/team/{team}', 'MatchController@detach');
            Route::put('{match}', 'MatchController@update');
            Route::delete('{match}', 'MatchController@destroy');
        });
        Route::prefix('recruit')->group(function () {
            Route::get('/', 'RecruitController@index');
            Route::get('{recruit}', 'RecruitController@show');
            Route::put('{recruit}', 'RecruitController@update');
            Route::delete('{recruit}', 'RecruitController@destroy');
        });
    });
});
