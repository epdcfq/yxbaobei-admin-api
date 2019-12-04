<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| 微信 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * 微信授权登录laravel-wechat
 */
// 响应微信服务
Route::any('/serve', 'ServeController@serve');

// Route::group(['prefix'=>'wechat', 'namespace'=>'Wechat'], function(){
// 	Route::any('/serve', 'ServeController@serve');
// 	// Route::group([])
// });
