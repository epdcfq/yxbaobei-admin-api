<?php

use Illuminate\Http\Request;

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

/**
 * JWT登录验证
 * 
 */
Route::any('user/login', 'Jwt\AuthController@login');
Route::any('user/register', 'Jwt\AuthController@register');

Route::group(['middleware' => 'auth.jwt'], function () {
	// 登录用户信息
	Route::group(['prefix'=>'user'], function(){
		// 退出登录
	    Route::any('/logout', 'Jwt\AuthController@logout');
	    // 获取用户信息
	    Route::any('/info', 'Jwt\AuthController@info');
	});

	// 权限管理
	Route::group(['namespace'=>'Adm\Sys'], function(){
		// 退出登录
	    Route::resource('/roles', 'RolesController');
	    // 获取用户信息
	    // Route::any('/info', 'Jwt\AuthController@user');
	});

});



	// 权限管理
	Route::group(['namespace'=>'Adm\Sys'], function(){
		// 退出登录
	    // Route::get('/roles/list', 'RoleController@list');
	    // 获取用户信息
	    // Route::any('/info', 'Jwt\AuthController@user');
	});
