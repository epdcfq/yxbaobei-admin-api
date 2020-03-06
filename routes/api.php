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
|  创建资源类控制器  php artisan make:controller Org/ChannelController --resource
*/
Route::group(['prefix'=>'auth', 'namespace'=>'UCenter'], function($router){
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



Route::get('company/name', 'HomeController@company');

Route::group(['prefix'=>'test', 'namespace'=>'Test'], function(){
	Route::any('/db/index', 'DbController@index');
});

/**
 * JWT登录验证
 * 
 */
Route::any('user/login', 'Jwt\AuthController@login');
Route::any('user/register', 'Jwt\AuthController@register');

// Route::group(['middleware' => 'jwt.auth'], function () {
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

	// 门店路径
	Route::group(['prefix'=>'org', 'namespace'=>'Org'], function(){
		// 资讯资源路由
		Route::resource('/goods', 'GoodsController');
		// 栏目路由
		Route::resource('/channel', 'ChannelController');
		// 分类资源路由
		Route::resource('/categories', 'CategoryController');
		// 搜索项查询
		Route::any('/search/options/{name}', function(
			\App\Http\Controllers\Org\SearchOptionController $index, 
			$name, 
			\Illuminate\Http\Request $request) {
			return $index->$name($request);
		});
		// 门店管理资源类
		Route::resource('/info', 'OrgInfoController');
		
		Route::any('/search/options/{name}', function(App\Http\Controllers\Org\SearchOptionController $index, $name, \Illuminate\Http\Request $request) {
			return $index->$name($request);
		});
		Route::any('/show', 'OrgInfoController@show');
		Route::any('/store', 'OrgInfoController@store');



	});
		// 客户中心
	Route::group(['prefix'=>'ucenter', 'namespace'=>'UCenter'], function(){
		Route::resource('customer', 'CustomerController');
	});
	
// });
// Route::any('/org/search/article', 'Org\SearchOptionController@article');
// // 搜索项查询
Route::any('/org/search/options/{name}', function(App\Http\Controllers\Org\SearchOptionController $index, $name, \Illuminate\Http\Request $request) {
	return $index->$name($request);
});

Route::get('/org/categories/options', 'Org\CategoryController@options');
// 上传路由
Route::group(['namespace'=>'Upload', 'middleware'=>'cors', 'prefix'=>'upload'], function(){
	// 退出登录
    Route::any('/image/{path?}', 'ImageController@index');
    // 获取用户信息
    // Route::any('/info', 'Jwt\AuthController@user');
});
