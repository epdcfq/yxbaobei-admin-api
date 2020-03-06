<?php

/*
|--------------------------------------------------------------------------
| Ucenter Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 登录路由
Route::group(['prefix'=>'auth'], function($router){
	# 登录(手机号+密码)
    Route::any('loginbypwd', 'AuthController@loginbypwd');
    # 登录(手机号+验证码)
    Route::any('loginbycode', 'AuthController@loginbycode');

    # 当前登录用户信息(手机号+验证码)
    Route::any('info', 'AuthController@info');

	# 退出
    Route::any('logout', 'AuthController@logout');
    # 刷新jwt-token
    Route::any('refresh', 'AuthController@refresh');
    # 我的
    Route::any('me', 'AuthController@me');
    /****** 微信授权  ******/
    Route::any('code2session', 'AuthController@code2session');
});