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
Route::group(['middleware' => 'app.token'], function ($router) {
	Route::group([  'prefix' => 'user'], function () {
		Route::post('/login', 'API\UserController@login');
		Route::post('/register', 'API\UserController@register');
		Route::post('/reset_password', 'API\UserController@resetPassword');
		Route::post('/reset_password_action', 'API\UserController@resetPasswordAction');
	});
	//Jobs
	Route::get('/search/jobs', 'API\JobsController@index');
});
Route::group(['middleware' => 'user.token'], function ($router) {
	Route::get('/friend/list', 'API\FriendController@index'); // PENDING GA JELAS ALURNYA
});
