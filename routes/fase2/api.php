<?php

use Illuminate\Http\Request;
//Fase 2 API
	//General

Route::group(['middleware' => ['app.token', 'cors','log.route']], function ($router) {
	Route::get('/country', 'General\LocationController@country');
	Route::get('/province', 'General\LocationController@province');
	Route::get('/city', 'General\LocationController@city');
	Route::get('/district', 'General\LocationController@district');
	Route::get('/sub-district', 'General\LocationController@subDistrict');
	Route::post('/user/request-verify', 'API\UserController@RequestVerifyMail');
	Route::get('/news/comment', 'API\NewsController@getComment');
});
Route::group(['middleware' => ['user.token', 'cors','log.route']], function ($router) {
	//News Comment
	Route::post('/news/comment', 'API\NewsController@addComment');
	Route::delete('/news/comment', 'API\NewsController@deleteComment');
	Route::get('/news/comment/detail', 'API\NewsController@getCommentDetail');
	Route::get('/news/reply-comment', 'API\NewsController@getReplyComment');

	//Job filter
	Route::get('/job/type-list', 'API\JobsController@getJobTypeList');
	Route::get('/job', 'API\JobsController@index');

	//mail verified
	Route::get('/user/check-email', 'API\UserController@checkmailVerified');
	Route::get('/user/verify-email', 'API\UserController@VerifyMail');
});
