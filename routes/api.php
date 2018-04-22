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
    // Non-login APIs
    Route::middleware(['throttle:6000,1', 'log'])->group(function () {
        Route::prefix('post')->group(function () {
            Route::get('/', 'PostController@index');
            Route::get('{post}', 'PostController@show');
        });
        Route::prefix('match')->group(function () {
            Route::get('/', 'MatchController@index');
            Route::get('{match}', 'MatchController@show');
        });
        Route::prefix('recruit')->group(function () {
            Route::get('/', 'RecruitController@index');
            Route::get('tags', 'RecruitController@tags');
        });
    });
    // User's APIs
    Route::middleware(['auth', 'throttle:60,1', 'log:true'])->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', 'UserController@show');
            Route::put('/', 'UserController@update');
        });
        Route::prefix('match')->group(function () {
            Route::post('{match}/apply', 'MatchController@apply');
            Route::post('{match}/cancel', 'MatchController@cancel');
        });
        Route::prefix('team')->group(function () {
            Route::get('/', 'TeamController@index');
            Route::post('/', 'TeamController@store');
            Route::put('{team}', 'TeamController@update');
            Route::post('{team}/verify', 'TeamController@verify');
            Route::delete('{team}', 'TeamController@destroy');
        });
        Route::prefix('recruit')->group(function () {
            Route::get('current_user', 'RecruitController@currentUser');
            Route::post('/', 'RecruitController@store');
            Route::put('{recruit}', 'RecruitController@update');
            Route::delete('{recruit}', 'RecruitController@destroy');
        });
    });
    // Admin's APIs
    Route::prefix('admin')->namespace('Admin')->middleware(['auth', 'throttle:600,1', 'admin'])->group(function () {
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
            Route::prefix('snapshot')->group(function () {
                Route::get('/', 'MatchSnapshotController@index');
                Route::get('{match_snapshot}', 'MatchSnapshotController@show');
                Route::get('{match_snapshot}/user', 'MatchSnapshotController@user');
                Route::get('{match_snapshot}/user/export', 'MatchSnapshotController@export');
            });
            Route::get('/', 'MatchController@index');
            Route::post('/', 'MatchController@store');
            Route::get('{match}', 'MatchController@show');
            Route::get('{match}/team', 'MatchController@team');
            Route::post('{match}/apply', 'MatchController@apply');
            Route::delete('{match}/team/{team}', 'MatchController@detach');
            Route::post('{match}/team/alloc_number', 'MatchController@allocNumber');
            Route::put('{match}', 'MatchController@update');
            Route::delete('{match}', 'MatchController@destroy');
            Route::post('{match}/snapshot', 'MatchController@snapshot');
        });
        Route::prefix('recruit')->group(function () {
            Route::get('/', 'RecruitController@index');
            Route::get('{recruit}', 'RecruitController@show');
            Route::put('{recruit}', 'RecruitController@update');
            Route::delete('{recruit}', 'RecruitController@destroy');
        });
        Route::prefix('upload')->group(function () {
            Route::post('image', 'UploadController@storeImage');
        });
    });
});
