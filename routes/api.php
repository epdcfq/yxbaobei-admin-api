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
	    Route::any('/info', 'Jwt\AuthController@user');
	});

    // Route::get('products', 'ProductController@index');
    // Route::get('products/{id}', 'ProductController@show');
    // Route::post('products', 'ProductController@store');
    // Route::put('products/{id}', 'ProductController@update');
    // Route::delete('products/{id}', 'ProductController@destroy');
});
// // 注册
// Route::post('auth/register', 'Jwt\AuthController@register');
// // 登录
// Route::post('auth/login', 'Jwt\AuthController@login');
// // 登录用户信息
// Route::group(['middleware' => 'jwt.auth'], function(){
//   Route::get('auth/user', 'Jwt\AuthController@user');
// });
// // 刷新登录
// Route::group(['middleware' => 'jwt.refresh'], function(){
//   Route::get('auth/refresh', 'Jwt\AuthController@refresh');
// });





// 获取登录用户信息
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return ['code'=>0, 'data'=>$request->user(), 'msg'=>'success'];
// });


// Route::any('/user/login', function(Request $request) {
// 	exit(json_encode(['code'=>20000, 'data'=>'admin_token_api']));
// });
// Route::any('/', function(Request $request) {
// 	exit(json_encode(['code'=>20000, 'data'=>'admin_token_api_index']));
// });


// Route::get('/test', function(Request $request) {
// 	return Response()->json([], 200, 'fdsafds');
// });