<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'users/'], function () {
        Route::post('register', 'UsersController@register');
        Route::post('login', 'UsersController@login');
        Route::group(['middleware' => 'auth'], function () {
            // Notification
            Route::post('devices','ApiMobNotifications@updateDevice');
            Route::get('notifications','ApiMobNotifications@notifications');
            Route::delete('notifications/{notification}','ApiMobNotifications@delete');
            // Profile
            Route::get('profile', 'UsersController@show');
            Route::match(['PATCH', 'PUT'], 'profile', 'UsersController@update');
        });
    });

    Route::group(['middleware' => 'auth'], function () {

        Route::group(['prefix' => 'posts'], function () {
            Route::get('types', 'TypesController@index');
            Route::post('/', 'PostsController@store');
            Route::get('/', 'PostsController@index');
            Route::group(['prefix' => '/{post}'], function () {
                Route::post('comments', 'CommentsController@store');
                Route::post('unfollow', 'ApiMobNotifications@unfollow');
                Route::get('/', 'PostsController@show');
                Route::delete('/', 'PostsController@destroy');
                Route::match(['PUT', 'PATCH'], '/', 'PostsController@update');
            });
        });
        Route::match(['PUT', 'PATCH'], '/comments/{comment}', 'CommentsController@edit');
    });

});
