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
// Route::any('/user/login', function(Request $request) {
// 	exit(json_encode(['code'=>20000, 'data'=>'admin_token_web']));
// });


// /**
//  * Passport OAuth认证
//  */

// # 前端路由页面
// Route::get('/passport', function () {
//     return view('passport');
// });
// # 跳转页面
// Route::get('/redirect', function () {
//     $query = http_build_query([
//         'client_id' => 'client-id',
//         'redirect_uri' => 'http://127.0.0.1:8000/callback',
//         'response_type' => 'code',
//         'scope' => '',
//     ]);

//     return redirect('http://your-app.com/oauth/authorize?'.$query);
// });
// # 回调页面
// Route::get('/callback', function (Request $request) {
//     $http = new GuzzleHttp\Client;

//     $response = $http->post('http://127.0.0.1:8000/oauth/token', [
//         'form_params' => [
//             'grant_type' => 'authorization_code',
//             'client_id' => 'client-id',
//             'client_secret' => 'client-secret',
//             'redirect_uri' => 'http://example.com/callback',
//             'code' => $request->code,
//         ],
//     ]);

//     return json_decode((string) $response->getBody(), true);
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
