<?php

use Illuminate\Http\Request;
//Fase 2 API
	//News Comment
	Route::post('/news/comment', 'API\NewsController@addComment');
	Route::delete('/news/comment', 'API\NewsController@deleteComment');
	Route::get('/news/comment', 'API\NewsController@getComment');
	Route::get('/news/comment/detail', 'API\NewsController@getCommentDetail');
	Route::get('/news/reply-comment', 'API\NewsController@getReplyComment');

	//Job filter
	Route::get('/job/type-list', 'API\JobsController@getJobTypeList');
	Route::post('/job', 'API\JobsController@index');
