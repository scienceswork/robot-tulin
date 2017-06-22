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

Route::any('/wechat', [
    'as' => 'wechatValidate',
    'uses' => 'wechatController@wechatValidate'
]);

Route::get('/users', [
    'as' => 'getUsers',
    'uses' => 'wechatController@getUsers'
]);