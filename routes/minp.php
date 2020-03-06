<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| 小程序 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * 小程序授权登录laravel-wechat
 */
// 
Route::group(['prefix'=>'user'])


// 测试
Route::group(['prefix'=>'test', 'namespace'=>'Test'], function(){
	# 获取access_token
	Route::get('/access_token', 'TestController@access_token');
	# 微信ip列表
	Route::get('/wxips', 'TestController@wxips');
	# 按钮操作
	Route::get('/menu/{opt}', 'TestController@menu');
	# 用户信息
	Route::get('authorize/info', 'AuthorizeController@info');
	# 用户标签
	Route::get('tag/{opt}', 'TestController@tag');
	# 消息测试
	Route::get('msg', 'MessageController@msg');
	Route::get('msg/template', 'MessageController@template');

	# 事件推送测试
	Route::get('event/{opt?}', 'MessageController@event');

});