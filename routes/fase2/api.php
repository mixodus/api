<?php

use Illuminate\Http\Request;
//Fase 2 API
	//News Comment
	Route::post('/news/comment', 'API\NewsController@comment'); //reply and comment jadi 1 dibedakan dengan type
	Route::delete('/news/comment', 'API\NewsController@deleteComment');
	Route::get('/news/comment', 'API\NewsController@getComment'); //reply and comment jadi 1 dibedakan dengan type

