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

	//hackathon
	Route::get('/event/hackathon', 'API\EventController@Hackathon');
	Route::get('/event/hackathon/terms-condition', 'API\EventController@HackathonTerms');
	Route::get('/event/hackathon/semester', 'API\EventController@HackathonSemester');
});
Route::group(['middleware' => ['user.token', 'cors','log.route']], function ($router) {
	//hackathon
	Route::post('/event/hackathon', 'API\EventController@RegisterHackathon');
	Route::post('/event/hackathon/file', 'API\EventController@HackathonUploadFile');
	Route::get('/event/hackathon/reset', 'API\EventController@ResetRegisterHackathon');
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

	//cv
	Route::get('/user-cv', 'API\UserCVController@index');
	Route::post('/user-cv', 'API\UserCVController@create');
	Route::delete('/user-cv', 'API\UserCVController@delete');

	//referral
	Route::post('/referral/upload-cv/{id}', 'API\ReferralController@uploadCV');

	
	//device token - notification
	Route::post('/user/device-token', 'API\UserController@DeviceToken');
});
