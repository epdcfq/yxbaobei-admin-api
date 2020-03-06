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
// 首页
Route::get('/home', 'HomeController@index')->name('home');

// 网页路由
Route::group(['prefix'=>'/web/{orgId}', 'namespace'=>'Web'],
	function() {
		Route::group(['namespace'=>'Pc', 'prefix'=>'pc'], function() {
			Route::get('/index', 'HomeController@index')->name('pc_index');
			Route::get('/about', 'HomeController@about')->name('pc_about');
			Route::get('/article', 'HomeController@article')->name('pc_article');
			Route::get('/article_show', 'HomeController@articleShow')->name('pc_article_show');

		});
	}
);

Route::group(['prefix'=>'/web/collect/', 'namespace'=>'Web\\Test'], function(){
	Route::get('/', 'IndexController@index');
	Route::get('/test', 'IndexController@test');

});

