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

//verify mail
Route::get('/site/check-reset', 'API\UserController@checkResetPassword');
Route::get('/sites', 'API\GeneralController@site');
Route::get('/site/user/check-verify', 'API\UserController@checkmailVerify');
Route::get('/site/user/check-verify-change-email', 'API\UserController@checkmailVerifyChangeEmail');
Route::get('/sites/page-verify', 'API\UserController@PageVerify');
Route::post('/site/reset_password_action', 'API\UserController@resetPasswordAction');
Route::group(['middleware' => ['app.token', 'cors'], 'prefix' => 'user'], function ($router) {
	Route::post('/login', 'API\UserController@login')->middleware('log.route:user,Login,action');
	Route::post('/register', 'API\UserController@register')->middleware('log.route:user,register,action');
	Route::post('/reset_password', 'API\UserController@resetPassword')->middleware('log.route:user,Reset-Password,action');
});
Route::group(['middleware' => ['user.token','cors','log.route']], function ($router) {
	//friend --API di hide di existing mobile (production)
	Route::get('/friend/list', 'API\FriendController@index'); 
	Route::get('/friend/list_id_only', 'API\FriendController@listingId'); 
	Route::get('/friend/mutual_friends', 'API\FriendController@mutual'); 
	Route::get('/friend/mutual_friends_id_only', 'API\FriendController@mutualId'); 
	Route::get('/friend/list_friend_request', 'API\FriendController@friendRequestList');  
	Route::get('/friend/list_friend_request_id', 'API\FriendController@friendRequestId'); 
	Route::post('/friend/add_friend', 'API\FriendController@add');
	Route::post('/friend/approve', 'API\FriendController@approve');
	Route::post('/friend/unfriend', 'API\FriendController@unfriend');
	Route::post('/friend/reject', 'API\FriendController@reject');

	//bank account --API tidak ada record di existing mobile (production)
	Route::get('/accounts/list', 'API\UserBankAccountController@index');
	Route::post('/accounts/add', 'API\UserBankAccountController@add');
	Route::put('/accounts/edit', 'API\UserBankAccountController@update');
	Route::delete('/accounts', 'API\UserBankAccountController@delete');

	//withdraw --API tidak ada record di existing mobile (production)
	Route::get('/withdraw', 'API\UserWithdrawController@index');
	Route::get('/withdraw/results', 'API\UserWithdrawController@index');
	Route::get('/withdraw/history', 'API\UserWithdrawController@history');
	Route::post('/withdraw/check', 'API\UserWithdrawController@check');

	Route::post('/upload/upload/{id}', 'API\CertificationController@upload')->middleware('log.route:user-data,Upload-Certification,action');
});

	Route::post('/upload', 'General\UploadController@index'); // belum tau fungsinya untuk dimana
	Route::post('/upload/do_upload', 'General\UploadController@upload'); // belum tau fungsinya untuk dimana
	
	//API
	//Jobs search
	Route::get('/search/jobs', 'API\JobsController@index');
	//search
	Route::get('/search/generalsearch', 'API\GeneralController@generalSearch');
	Route::get('/search/reference', 'API\GeneralController@referenceSeacrh'); 

///Dashboard
Route::group(['prefix' => 'admin'], function () {
	Route::post('/login', 'API\Dashboard\AuthUser\AdminController@login');
	Route::post('/register', 'API\Dashboard\AuthUser\AdminController@register');
	Route::post('/reset_password', 'API\Dashboard\AuthUser\AdminController@resetPassword');
	Route::post('/reset_password_action', 'API\Dashboard\AuthUser\AdminController@resetPasswordAction');
});
///endDashboard

	

