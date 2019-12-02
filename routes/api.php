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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::any('/user/login', function(Request $request) {
	exit(json_encode(['code'=>20000, 'data'=>'admin_token_api']));
});
Route::any('/', function(Request $request) {
	exit(json_encode(['code'=>20000, 'data'=>'admin_token_api_index']));
});
