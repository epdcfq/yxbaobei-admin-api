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
Route::any('/sign/check', 'ServeController@checkSign');

// 微信用户授权
Route::group(['prefix'=>'user', 'middleware'=>'wechat.oauth'], function(){
	Route::get('/info', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料

        dd($user);
    });
});