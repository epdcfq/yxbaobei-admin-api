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


Route::any('/sign/valid', 'ServeController@valid');

// 微信用户授权
Route::group(['prefix'=>'user', 'middleware'=>'wechat.oauth'], function(){
	Route::get('/info', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料

        dd($user);
    });
});

Route::any('/auth', 'WxUserController@auth');

// [Web端]JSSDK配置
Route::any('/sdk/config', 'WxSdkController@config');
// 微信授权
Route::group(['prefix'=>'authorize'], function(){
	# 发起授权跳转
	Route::get('/{source?}/{source_id?}', 'WxOauthController@index');
	Route::get('/callback/{source?}/{source_id?}', 'WxOauthController@callback');
});

// 测试
Route::group(['prefix'=>'test'], function(){
	# 获取access_token
	Route::get('/access_token', 'TestController@access_token');
	# 微信ip列表
	Route::get('/wxips', 'TestController@wxips');
	# 按钮操作
	Route::get('/menu/{opt}', 'TestController@menu');
	# 用户信息
	Route::get('user/{opt}', 'TestController@user');
	# 用户标签
	Route::get('tag/{opt}', 'TestController@tag');
});